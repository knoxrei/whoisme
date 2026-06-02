<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $mailSubject }}</title>
</head>
<body style="font-family: monospace, sans-serif; background: #0a0a0a; color: #e5e5e5; padding: 24px;">
    <div style="max-width: 560px; margin: 0 auto; border: 1px solid #7f1d1d; padding: 24px; background: #050505;">
        <p style="color: #ef4444; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; margin: 0 0 16px;">DoxMe Broadcast</p>
        <p style="margin: 0 0 12px;">Hello <strong>{{ $username }}</strong>,</p>
        <div style="font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $mailMessage }}</div>
        <p style="margin: 24px 0 0; font-size: 11px; color: #6b7280;">— DoxMe Operations</p>
    </div>
</body>
</html>
