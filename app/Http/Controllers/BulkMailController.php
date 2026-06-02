<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Jobs\SendBulkMailToUserJob;
use App\Models\BulkMailCampaign;
use App\Services\BulkMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BulkMailController extends Controller
{
    public function __construct(
        protected BulkMailService $bulkMailService
    ) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->identification?->role;

        if ($role !== Role::OWNER) {
            abort(403);
        }

        $title = 'Bulk Mail Broadcast';

        $campaigns = BulkMailCampaign::query()
            ->with('creator:id,username')
            ->latest()
            ->limit(10)
            ->get();

        $activeCampaign = $campaigns->first(fn (BulkMailCampaign $c) => ! $c->isFinished());

        return view('dashboard.bulk-mail', [
            'title' => $title,
            'role' => $role,
            'totalWithEmail' => $this->bulkMailService->countRecipients(false),
            'totalVerified' => $this->bulkMailService->countRecipients(true),
            'defaultTimeoutSeconds' => $this->bulkMailService->defaultTimeoutSeconds(),
            'minTimeoutSeconds' => $this->bulkMailService->minTimeoutSeconds(),
            'maxTimeoutSeconds' => $this->bulkMailService->maxTimeoutSeconds(),
            'campaigns' => $campaigns,
            'activeCampaign' => $activeCampaign,
        ]);
    }

    public function dispatch(Request $request): RedirectResponse
    {
        $this->authorizeOwner();

        $minTimeout = $this->bulkMailService->minTimeoutSeconds();
        $maxTimeout = $this->bulkMailService->maxTimeoutSeconds();

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
            'verified_only' => 'sometimes|boolean',
            'timeout_seconds' => ['required', 'integer', Rule::between($minTimeout, $maxTimeout)],
        ]);

        $verifiedOnly = $request->boolean('verified_only');
        $timeoutSeconds = $this->bulkMailService->normalizeTimeout((int) $validated['timeout_seconds']);

        $userIds = $this->bulkMailService
            ->recipientsQuery($verifiedOnly)
            ->pluck('id')
            ->all();

        if ($userIds === []) {
            return back()->with('error', 'No recipients found for this audience.');
        }

        $campaign = BulkMailCampaign::query()->create([
            'created_by' => auth()->id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'verified_only' => $verifiedOnly,
            'timeout_seconds' => $timeoutSeconds,
            'total_recipients' => count($userIds),
            'status' => 'queued',
        ]);

        foreach ($userIds as $userId) {
            SendBulkMailToUserJob::dispatch(
                $userId,
                $campaign->id,
                $validated['subject'],
                $validated['message'],
                $timeoutSeconds,
            );
        }

        $workerTimeout = $timeoutSeconds + 5;

        return redirect()
            ->route('dashboard.bulk-mail')
            ->with('success', 'Broadcast queued for '.number_format(count($userIds)).' recipients (timeout: '.$timeoutSeconds.'s per email). Run: php artisan queue:work --timeout='.$workerTimeout);
    }

    protected function authorizeOwner(): void
    {
        if (auth()->user()->identification?->role !== Role::OWNER) {
            abort(403);
        }
    }
}
