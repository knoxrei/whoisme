<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Services\BulkMailService;
use Illuminate\Http\JsonResponse;
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

        return view('dashboard.bulk-mail', [
            'title' => $title,
            'role' => $role,
            'totalWithEmail' => $this->bulkMailService->countRecipients(false),
            'totalVerified' => $this->bulkMailService->countRecipients(true),
            'timeoutSeconds' => $this->bulkMailService->timeoutSeconds(),
        ]);
    }

    public function recipients(Request $request): JsonResponse
    {
        $this->authorizeOwner();

        $verifiedOnly = $request->boolean('verified_only');

        $ids = $this->bulkMailService
            ->recipientsQuery($verifiedOnly)
            ->pluck('id')
            ->all();

        return response()->json([
            'user_ids' => $ids,
            'total' => count($ids),
        ]);
    }

    public function sendBatch(Request $request): JsonResponse
    {
        $this->authorizeOwner();

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
            'user_ids' => 'required|array|min:1|max:50',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $result = $this->bulkMailService->sendBatch(
            $validated['user_ids'],
            $validated['subject'],
            $validated['message']
        );

        return response()->json($result);
    }

    protected function authorizeOwner(): void
    {
        if (auth()->user()->identification?->role !== Role::OWNER) {
            abort(403);
        }
    }
}
