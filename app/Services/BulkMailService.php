<?php

namespace App\Services;

use App\Mail\BulkUserAnnouncementMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BulkMailService
{
    public function defaultTimeoutSeconds(): int
    {
        return $this->normalizeTimeout((int) config('platform.bulk_mail_timeout', 10));
    }

    public function minTimeoutSeconds(): int
    {
        return max(1, (int) config('platform.bulk_mail_timeout_min', 3));
    }

    public function maxTimeoutSeconds(): int
    {
        return max($this->minTimeoutSeconds(), (int) config('platform.bulk_mail_timeout_max', 120));
    }

    public function normalizeTimeout(int $seconds): int
    {
        return max($this->minTimeoutSeconds(), min($this->maxTimeoutSeconds(), $seconds));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<User>
     */
    public function recipientsQuery(bool $verifiedOnly = false)
    {
        $query = User::query()
            ->whereNotNull('email')
            ->where('email', '!=', '');

        if ($verifiedOnly) {
            $query->whereNotNull('email_verified_at');
        }

        return $query->orderBy('id');
    }

    public function countRecipients(bool $verifiedOnly = false): int
    {
        return $this->recipientsQuery($verifiedOnly)->count();
    }

    /**
     * @return array{status: string, reason?: string, elapsed_ms?: int}
     */
    public function sendToUser(User $user, string $subject, string $message, ?int $timeoutSeconds = null): array
    {
        $email = trim((string) $user->email);

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'skipped', 'reason' => 'invalid_email'];
        }

        $timeout = $this->normalizeTimeout($timeoutSeconds ?? $this->defaultTimeoutSeconds());
        $previousSocketTimeout = ini_get('default_socket_timeout');
        ini_set('default_socket_timeout', (string) $timeout);

        $startedAt = microtime(true);

        try {
            Mail::to($email)->send(new BulkUserAnnouncementMail(
                $user->username,
                $subject,
                $message
            ));

            $elapsedMs = (int) round((microtime(true) - $startedAt) * 1000);

            if ($elapsedMs >= ($timeout * 1000)) {
                return ['status' => 'skipped', 'reason' => 'timeout', 'elapsed_ms' => $elapsedMs];
            }

            return ['status' => 'sent', 'elapsed_ms' => $elapsedMs];
        } catch (\Throwable $e) {
            Log::warning('Bulk mail skipped', [
                'user_id' => $user->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return ['status' => 'skipped', 'reason' => 'send_failed'];
        } finally {
            ini_set('default_socket_timeout', (string) $previousSocketTimeout);
        }
    }

    /**
     * @return array{sent: int, skipped: int, results: list<array<string, mixed>>}
     */
    public function sendBatch(array $userIds, string $subject, string $message): array
    {
        $users = User::query()->whereIn('id', $userIds)->orderBy('id')->get();

        $sent = 0;
        $skipped = 0;
        $results = [];

        foreach ($users as $user) {
            $outcome = $this->sendToUser($user, $subject, $message);
            $row = [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'status' => $outcome['status'],
                'reason' => $outcome['reason'] ?? null,
            ];
            $results[] = $row;

            if ($outcome['status'] === 'sent') {
                $sent++;
            } else {
                $skipped++;
            }
        }

        return [
            'sent' => $sent,
            'skipped' => $skipped,
            'results' => $results,
        ];
    }
}
