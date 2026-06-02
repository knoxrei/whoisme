<?php

namespace App\Http\Controllers;

use App\Helper\PastebinPost;
use App\Helper\VisitorTracker;
use App\Http\Requests\PastebinRequest;
use App\Models\Pastebin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use League\CommonMark\GithubFlavoredMarkdownConverter;


class PastebinController extends Controller
{
    public function index(): View
    {
        $title = 'Create New Doxxing';
        return view('pastebin.create', compact('title'));
    }

    public function show(string $slug): View
    {
        $pastebin = Pastebin::where('slug', $slug)->firstOrFail();
        
        // Check if password protected
        if ($pastebin->password && !session()->has('pastebin_unlocked_' . $pastebin->id)) {
            $title = 'Password Protected';
            return view('pastebin.password', compact('pastebin', 'title'));
        }

        $pastebin->increment('views_count');
        $title = $pastebin->title;
        
        // Get pending edits if user is author or admin
        $pendingEdits = [];
        if (auth()->check() && (auth()->id() === $pastebin->user_id || auth()->user()->canUsePremiumFeatures())) {
            $pendingEdits = $pastebin->edits()->where('status', 'pending')->with('user')->get();
        }

        $comments = $pastebin->comments()->with('user.identification')->latest()->take(5)->get()->reverse();
        $converter = new GithubFlavoredMarkdownConverter();
        $contentMarkdown = $converter->convert($pastebin->content);

        // Self-Destruct / Burn After Reading Logic
        $isBurned = false;
        if ($pastebin->is_self_destruct) {
            $isBurned = true;

            // Decrement user reputation if public
            if ($pastebin->visibility === \App\Enum\Visibility::PUBLIC && $pastebin->user_id) {
                $authorIdentification = \App\Models\Identification::where('user_id', $pastebin->user_id)->first();
                if ($authorIdentification) {
                    $authorIdentification->decrement('reputation', 1);
                }
            }

            // Purge gallery files
            foreach ($pastebin->images as $image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            // Purge cover if custom
            if ($pastebin->cover_path && $pastebin->cover_path !== 'defaultCover.png') {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pastebin->cover_path);
            }

            // Purge related comments and suggestions
            $pastebin->comments()->delete();
            $pastebin->edits()->delete();

            // Permanently wipe the database entry
            $pastebin->delete();
        }

        // Track visitor on this pastebin page
        VisitorTracker::trackPastebin($slug);

        $visitorSnapshot = VisitorTracker::getPastebinVisitorSnapshot($slug);
        $visitors = $visitorSnapshot['visitors'];
        $visitorCount = $visitorSnapshot['count'];

        return view('pastebin.show', compact('pastebin', 'title', 'pendingEdits', 'comments', 'contentMarkdown', 'isBurned', 'visitors', 'visitorCount'));
    }

    public function unlock(Request $request, string $slug)
    {
        $pastebin = Pastebin::where('slug', $slug)->firstOrFail();
        
        if (\Illuminate\Support\Facades\Hash::check($request->password, $pastebin->password)) {
            session()->put('pastebin_unlocked_' . $pastebin->id, true);
            return redirect()->route('pastebin.show', $slug);
        }

        return back()->withErrors(['password' => 'Invalid decryption key. Access denied.']);
    }

    /**
     * Track visitor heartbeat for a pastebin page (AJAX).
     */
    public function trackVisit(Request $request, string $slug): \Illuminate\Http\JsonResponse
    {
        Pastebin::where('slug', $slug)->firstOrFail();
        VisitorTracker::trackPastebin($slug);

        $visitorSnapshot = VisitorTracker::getPastebinVisitorSnapshot($slug);
        $visitors = $visitorSnapshot['visitors'];

        return response()->json([
            'visitors' => $visitors,
            'count'    => $visitorSnapshot['count'],
        ]);
    }

    /**
     * Get live visitor list for a pastebin page (AJAX).
     */
    public function getVisitors(string $slug): \Illuminate\Http\JsonResponse
    {
        Pastebin::where('slug', $slug)->firstOrFail();
        $visitorSnapshot = VisitorTracker::getPastebinVisitorSnapshot($slug);
        $visitors = $visitorSnapshot['visitors'];

        return response()->json([
            'visitors' => $visitors,
            'count'    => $visitorSnapshot['count'],
        ]);
    }

    public function store(PastebinRequest $request)
    {
        $validate = $request->validated();

        $title = $validate['title'];
        $generatedSlug = \Illuminate\Support\Str::slug($title);

        $existing = Pastebin::where('title', $title)
            ->orWhere('slug', $generatedSlug)
            ->first();

        if ($existing) {
            return redirect()->route('pastebin.show', $existing->slug)
                ->with('info', 'Someone has already submitted/posted this target. Redirecting to the existing record.');
        }

        // check if user is logged or not
        if (!auth()->check()) {
            $slug = PastebinPost::pastebinAnonymoust($validate);
            return redirect()->route('pastebin.show', $slug);
        } else {
            if (auth()->user()->identification?->role === \App\Enum\Role::BANNED) {
                return back()->withErrors(['error' => 'Your signature has been banned from publishing new pastes.']);
            }
            $slug = PastebinPost::pastebinAuthenticated($validate);
            return redirect()->route('pastebin.show', $slug);
        }
    }

    public function update(PastebinRequest $request, Pastebin $pastebin)
    {
        $this->authorize('update', $pastebin);

        if (auth()->user()->identification?->role === \App\Enum\Role::BANNED) {
            abort(403, 'Your signature has been banned.');
        }

        $validated = $request->validated();

        if ($request->hasFile('cover_path')) {
            $validated['cover_path'] = $request->file('cover_path')->store('cover', 'public');
        } else {
            unset($validated['cover_path']);
        }

        $pastebin->update($validated);

        // Handle deletion of existing images
        if ($request->has('delete_images')) {
            $imagesToDelete = \App\Models\ImagePastebin::whereIn('id', $request->delete_images)
                ->where('pastebin_id', $pastebin->id)
                ->get();

            foreach ($imagesToDelete as $image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imagePath = $image->store('uploadImages', 'public');
                \App\Models\ImagePastebin::create([
                    'image_path' => $imagePath,
                    'pastebin_id' => $pastebin->id,
                ]);
            }
        }

        return redirect()->route('pastebin.show', $pastebin->slug)->with('success', 'Pastebin updated successfully.');
    }

    public function destroy(Pastebin $pastebin)
    {
        $this->authorize('delete', $pastebin);

        // Decrement user reputation if public
        if ($pastebin->visibility === \App\Enum\Visibility::PUBLIC && $pastebin->user_id) {
            $authorIdentification = \App\Models\Identification::where('user_id', $pastebin->user_id)->first();
            if ($authorIdentification) {
                $authorIdentification->decrement('reputation', 1);
            }
        }

        // Delete associated image gallery files from storage
        foreach ($pastebin->images as $image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Delete cover if exists
        if ($pastebin->cover_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($pastebin->cover_path);
        }

        // Delete comments
        $pastebin->comments()->delete();

        // Delete edit suggestions
        $pastebin->edits()->delete();

        $pastebin->delete();

        return redirect()->route('dashboard')->with('success', 'Pastebin has been permanently deleted.');
    }

    public function report(Request $request, string $slug)
    {
        $pastebin = Pastebin::where('slug', $slug)->firstOrFail();

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        \App\Models\ReportPastebin::create([
            'pastebin_id' => $pastebin->id,
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Thank you. The report has been successfully submitted for review.');
    }

    /**
     * Render raw, plain-text content of the pastebin.
     */
    public function raw(string $slug)
    {
        $pastebin = Pastebin::where('slug', $slug)->firstOrFail();

        if ($pastebin->password && !session()->has('pastebin_unlocked_' . $pastebin->id)) {
            abort(403, 'Access denied. Decryption key required.');
        }

        return response($pastebin->content, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * Download the raw text of the pastebin as an attachment.
     */
    public function download(string $slug)
    {
        $pastebin = Pastebin::where('slug', $slug)->firstOrFail();

        if ($pastebin->password && !session()->has('pastebin_unlocked_' . $pastebin->id)) {
            abort(403, 'Access denied. Decryption key required.');
        }

        // Increment the download count statistic
        $pastebin->increment('download_count');

        $filename = $pastebin->slug . '.txt';

        return response($pastebin->content, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
