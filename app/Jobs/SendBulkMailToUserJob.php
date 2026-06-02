<?php

namespace App\Jobs;

use App\Models\BulkMailCampaign;
use App\Models\User;
use App\Services\BulkMailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class SendBulkMailToUserJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct(
        public int $userId,
        public int $campaignId,
        public string $subject,
        public string $message,
        public int $timeoutSeconds = 10,
    ) {
        $this->timeout = $timeoutSeconds + 5;
    }

    public function handle(BulkMailService $bulkMailService): void
    {
        $campaign = BulkMailCampaign::query()->find($this->campaignId);

        if (! $campaign || $campaign->isFinished()) {
            return;
        }

        if ($campaign->status === 'queued') {
            $campaign->update(['status' => 'processing']);
        }

        $user = User::query()->find($this->userId);

        if (! $user) {
            $this->recordOutcome('skipped');

            return;
        }

        $timeout = $campaign->timeout_seconds ?: $this->timeoutSeconds;
        $outcome = $bulkMailService->sendToUser($user, $this->subject, $this->message, $timeout);
        $this->recordOutcome($outcome['status'] === 'sent' ? 'sent' : 'skipped');
    }

    public function failed(?\Throwable $exception): void
    {
        $this->recordOutcome('skipped');
    }

    protected function recordOutcome(string $type): void
    {
        DB::transaction(function () use ($type) {
            $campaign = BulkMailCampaign::query()
                ->lockForUpdate()
                ->find($this->campaignId);

            if (! $campaign || $campaign->isFinished()) {
                return;
            }

            if ($type === 'sent') {
                $campaign->increment('sent_count');
            } else {
                $campaign->increment('skipped_count');
            }

            $campaign->increment('processed_count');
            $campaign->refresh();

            if ($campaign->processed_count >= $campaign->total_recipients) {
                $campaign->update(['status' => 'completed']);
            }
        });
    }
}
