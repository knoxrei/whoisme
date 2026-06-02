#!/usr/bin/env python3
"""
DoxMe bulk email broadcaster (CLI).

Reads recipients from the Laravel users table, skips null/invalid emails,
and sends HTML mail using the same dark/red theme as Laravel email templates.

Examples:
  # Test template to one address only
  python scripts/bulk_email.py --recipient you@example.com \\
      --subject "Platform Notice" --message "Hello from DoxMe."

  # Full broadcast (verified emails only)
  python scripts/bulk_email.py --subject "Update" --message "..." --verified-only

  # Dry run — list valid/skipped recipients without sending
  python scripts/bulk_email.py --subject "Update" --message "..." --dry-run
"""

from __future__ import annotations

import argparse
import html
import os
import re
import smtplib
import socket
import sys
from dataclasses import dataclass
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.utils import formataddr, parseaddr
from pathlib import Path

try:
    from dotenv import dotenv_values
except ImportError:
    dotenv_values = None

try:
    import pymysql
except ImportError:
    pymysql = None

DEFAULT_TIMEOUT = 50
SCRIPT_DIR = Path(__file__).resolve().parent
PROJECT_ROOT = SCRIPT_DIR.parent
TEMPLATE_PATH = SCRIPT_DIR / "templates" / "broadcast.html"


@dataclass
class Recipient:
    user_id: int | None
    username: str
    email: str


@dataclass
class MailConfig:
    host: str
    port: int
    username: str | None
    password: str | None
    from_address: str
    from_name: str
    use_tls: bool
    use_ssl: bool


def load_env() -> dict[str, str]:
    env: dict[str, str] = {}

    if dotenv_values is not None:
        dotenv_path = PROJECT_ROOT / ".env"
        if dotenv_path.exists():
            loaded = dotenv_values(dotenv_path)
            env.update({k: v for k, v in loaded.items() if v is not None})

    for key, value in os.environ.items():
        env[key] = value

    return env


def env_get(env: dict[str, str], key: str, default: str = "") -> str:
    value = env.get(key, default)
    if value is None:
        return default
    return str(value).strip().strip('"').strip("'")


def normalize_email(raw: str | None) -> str | None:
    if raw is None:
        return None

    email = str(raw).strip()
    if not email or email.lower() in {"null", "none", "n/a", "na"}:
        return None

    _, parsed = parseaddr(email)
    parsed = parsed.strip().lower()

    if not parsed or "@" not in parsed:
        return None

    local, _, domain = parsed.partition("@")
    if not local or not domain or "." not in domain:
        return None

    if not re.match(r"^[^@\s]+@[^@\s]+\.[^@\s]+$", parsed):
        return None

    return parsed


def load_template() -> str:
    if not TEMPLATE_PATH.exists():
        raise FileNotFoundError(f"Template not found: {TEMPLATE_PATH}")
    return TEMPLATE_PATH.read_text(encoding="utf-8")


def render_email(username: str, subject: str, message: str) -> str:
    template = load_template()
    safe_username = html.escape(username)
    safe_message = html.escape(message)

    return (
        template.replace("{{ username }}", safe_username)
        .replace("{{ subject }}", html.escape(subject))
        .replace("{{ message }}", safe_message)
    )


def build_mail_config(env: dict[str, str]) -> MailConfig:
    host = env_get(env, "MAIL_HOST", "127.0.0.1")
    port = int(env_get(env, "MAIL_PORT", "587") or "587")
    username = env_get(env, "MAIL_USERNAME") or None
    password = env_get(env, "MAIL_PASSWORD") or None
    from_address = env_get(env, "MAIL_FROM_ADDRESS", "hello@example.com")
    from_name = env_get(env, "MAIL_FROM_NAME", env_get(env, "APP_NAME", "DoxMe"))
    mailer = env_get(env, "MAIL_MAILER", "smtp").lower()
    encryption = env_get(env, "MAIL_ENCRYPTION", "").lower()

    use_ssl = port == 465 or encryption == "ssl"
    use_tls = encryption == "tls" or (port in {587, 2525} and not use_ssl)

    if mailer == "log":
        print("Warning: MAIL_MAILER=log — emails will not reach real inboxes.", file=sys.stderr)

    return MailConfig(
        host=host,
        port=port,
        username=username,
        password=password,
        from_address=from_address,
        from_name=from_name,
        use_tls=use_tls,
        use_ssl=use_ssl,
    )


def db_connect(env: dict[str, str]):
    if pymysql is None:
        raise RuntimeError("pymysql is required. Install: pip install -r scripts/requirements.txt")

    return pymysql.connect(
        host=env_get(env, "DB_HOST", "127.0.0.1"),
        port=int(env_get(env, "DB_PORT", "3306") or "3306"),
        user=env_get(env, "DB_USERNAME", "root"),
        password=env_get(env, "DB_PASSWORD", ""),
        database=env_get(env, "DB_DATABASE", "doxme"),
        charset="utf8mb4",
        cursorclass=pymysql.cursors.DictCursor,
        connect_timeout=10,
        read_timeout=30,
        write_timeout=30,
    )


def fetch_recipients(env: dict[str, str], verified_only: bool) -> list[dict]:
    sql = """
        SELECT id, username, email, email_verified_at
        FROM users
        ORDER BY id ASC
    """
    with db_connect(env) as conn:
        with conn.cursor() as cursor:
            cursor.execute(sql)
            rows = cursor.fetchall()

    if verified_only:
        rows = [row for row in rows if row.get("email_verified_at") is not None]

    return rows


def resolve_recipients(
    env: dict[str, str],
    verified_only: bool,
    test_recipient: str | None,
) -> tuple[list[Recipient], list[str]]:
    skipped: list[str] = []

    if test_recipient is not None:
        email = normalize_email(test_recipient)
        if email is None:
            raise ValueError(f"Invalid test recipient email: {test_recipient!r}")

        username = "Test User"
        try:
            with db_connect(env) as conn:
                with conn.cursor() as cursor:
                    cursor.execute(
                        "SELECT id, username FROM users WHERE LOWER(email) = %s LIMIT 1",
                        (email,),
                    )
                    row = cursor.fetchone()
                    if row:
                        username = row["username"]
        except Exception:
            pass

        return [Recipient(user_id=None, username=username, email=email)], skipped

    recipients: list[Recipient] = []
    seen_emails: set[str] = set()

    for row in fetch_recipients(env, verified_only):
        user_id = row.get("id")
        username = str(row.get("username") or "User")
        raw_email = row.get("email")
        email = normalize_email(raw_email)

        if email is None:
            skipped.append(f"user #{user_id} ({username}): null or invalid email ({raw_email!r})")
            continue

        if email in seen_emails:
            skipped.append(f"user #{user_id} ({username}): duplicate email ({email})")
            continue

        seen_emails.add(email)
        recipients.append(Recipient(user_id=user_id, username=username, email=email))

    return recipients, skipped


def send_email(
    config: MailConfig,
    recipient: Recipient,
    subject: str,
    html_body: str,
    timeout: int,
) -> None:
    msg = MIMEMultipart("alternative")
    msg["Subject"] = subject
    msg["From"] = formataddr((config.from_name, config.from_address))
    msg["To"] = formataddr((recipient.username, recipient.email))
    msg.attach(MIMEText(html_body, "html", "utf-8"))

    if config.use_ssl:
        server = smtplib.SMTP_SSL(config.host, config.port, timeout=timeout)
    else:
        server = smtplib.SMTP(config.host, config.port, timeout=timeout)

    try:
        server.ehlo()
        if config.use_tls and not config.use_ssl:
            server.starttls()
            server.ehlo()
        if config.username and config.password:
            server.login(config.username, config.password)
        server.sendmail(config.from_address, [recipient.email], msg.as_string())
    finally:
        try:
            server.quit()
        except Exception:
            pass


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description="DoxMe bulk email broadcaster")
    parser.add_argument("--recipient", help="Test mode: send only to this email address")
    parser.add_argument("--subject", required=True, help="Email subject line")
    parser.add_argument("--message", required=True, help="Plain-text message body")
    parser.add_argument(
        "--verified-only",
        action="store_true",
        help="Only users with a verified email (ignored in --recipient test mode)",
    )
    parser.add_argument(
        "--timeout",
        type=int,
        default=DEFAULT_TIMEOUT,
        help=f"SMTP timeout per email in seconds (default: {DEFAULT_TIMEOUT})",
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="List recipients and skips without sending mail",
    )
    return parser.parse_args()


def main() -> int:
    args = parse_args()

    if args.timeout < 1:
        print("Error: --timeout must be at least 1 second.", file=sys.stderr)
        return 1

    env = load_env()
    mail_config = build_mail_config(env)

    try:
        recipients, skipped = resolve_recipients(env, args.verified_only, args.recipient)
    except ValueError as exc:
        print(f"Error: {exc}", file=sys.stderr)
        return 1
    except Exception as exc:
        print(f"Database error: {exc}", file=sys.stderr)
        return 1

    if skipped:
        print(f"Skipped {len(skipped)} invalid/null/duplicate entries:")
        for line in skipped[:20]:
            print(f"  - {line}")
        if len(skipped) > 20:
            print(f"  ... and {len(skipped) - 20} more")

    if not recipients:
        print("No valid recipients to send to.")
        return 1

    mode = "TEST" if args.recipient else "BROADCAST"
    print(f"Mode: {mode}")
    print(f"Valid recipients: {len(recipients)}")
    print(f"Timeout: {args.timeout}s per email")

    if args.dry_run:
        print("\nDry run — no emails sent.")
        for recipient in recipients[:50]:
            print(f"  -> {recipient.username} <{recipient.email}>")
        if len(recipients) > 50:
            print(f"  ... and {len(recipients) - 50} more")
        return 0

    sent = 0
    failed = 0

    for index, recipient in enumerate(recipients, start=1):
        label = f"{recipient.username} <{recipient.email}>"
        html_body = render_email(recipient.username, args.subject, args.message)

        try:
            send_email(mail_config, recipient, args.subject, html_body, args.timeout)
            sent += 1
            print(f"[{index}/{len(recipients)}] SENT  {label}")
        except (smtplib.SMTPException, socket.timeout, TimeoutError, OSError) as exc:
            failed += 1
            print(f"[{index}/{len(recipients)}] SKIP  {label} ({exc})", file=sys.stderr)

    print(f"\nDone. Sent: {sent}, Failed/Skipped: {failed}, Invalid (pre-filter): {len(skipped)}")
    return 0 if failed == 0 else 2


if __name__ == "__main__":
    raise SystemExit(main())
