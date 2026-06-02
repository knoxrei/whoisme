<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Jobs\SendBulkMailToUserJob;
use App\Models\BulkMailCampaign;
use App\Services\BulkMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            'timeoutSeconds' => $this->bulkMailService->timeoutSeconds(),
            'campaigns' => $campaigns,
            'activeCampaign' => $activeCampaign,
        ]);
    }

    public function dispatch(Request $request): RedirectResponse
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
            'verified_only' => 'sometimes|boolean',
        ]);

        $verifiedOnly = $request->boolean('verified_only');
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
            'total_recipients' => count($userIds),
            'status' => 'queued',
        ]);

        foreach ($userIds as $userId) {
            SendBulkMailToUserJob::dispatch(
                $userId,
                $campaign->id,
                $validated['subject'],
                $validated['message'],
            );
        }

        return redirect()
            ->route('dashboard.bulk-mail')
            ->with('success', 'Broadcast queued for '.count($userIds).' recipients. Make sure `php artisan queue:work` is running.');
    }

    protected function authorizeOwner(): void
    {
        if (auth()->user()->identification?->role !== Role::OWNER) {
            abort(403);
        }
    }
}
