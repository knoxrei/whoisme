<?php

namespace App\Helper;

use App\Models\ImagePastebin;
use App\Models\Pastebin;
use Illuminate\Support\Str;

class PastebinPost
{
    /**
     * Create a new class instance.
     */



    // pastebin for anonymous user
    public static function pastebinAnonymoust($validatedData)
    {


        // if has cover
        if (isset($validatedData['cover_path'])) {
            $coverPath = $validatedData['cover_path']->store('cover', 'public');
        } else {
            $coverPath = 'defaultCover.png';
        }
        // if has picture  ( arrray )


        $pastebin = Pastebin::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'views_count' => 1,
            'download_count' => 0,
            'description' => $validatedData['description'] ?? null,
            'cover_path' => $coverPath,
            'user_id' => null,
            'author_name' => session('anonuser'),
            'password' => $validatedData['password'] ?? null,
            'visibility' => $validatedData['visibility'] ?? 'public',
            'is_self_destruct' => $validatedData['is_self_destruct'] ?? false,
        ]);

        // if has picture  ( arrray )
        if (isset($validatedData['image'])) {
            foreach ($validatedData['image'] as $image) {
                $imagePath = $image->store('uploadImages', 'public');
                $image = ImagePastebin::create([
                    'image_path' => $imagePath,
                    'pastebin_id' => $pastebin->id,
                ]);
            }
        }
        return $pastebin->slug;
    }

    // pastebin for authenticated user
    public static function pastebinAuthenticated($validateData)
    {
        $user = auth()->user();

        // if has cover
        if (isset($validateData['cover_path'])) {
            $coverPath = $validateData['cover_path']->store('cover', 'public');
        } else {
            $coverPath = 'defaultCover.png';
        }
        // if has picture  ( arrray )


        $pastebin = Pastebin::create([
            'title' => $validateData['title'],
            'content' => $validateData['content'],
            'views_count' => 1,
            'download_count' => 0,
            'description' => $validateData['description'] ?? null,
            'cover_path' => $coverPath,
            'user_id' => $user->id,
            'author_name' => $user->username,
            'password' => $validateData['password'] ?? null,
            'visibility' => $validateData['visibility'] ?? 'public',
            'is_self_destruct' => $validateData['is_self_destruct'] ?? false,
        ]);

        if ($pastebin->visibility === 'public') {
            // Reload fresh identification to avoid null issues
            $identification = \App\Models\Identification::firstOrCreate(
                ['user_id' => $user->id],
                ['reputation' => 0, 'role' => \App\Enum\Role::MEMBER->value]
            );
            $identification->increment('reputation', 1);

            // Flash reputation reward notification
            session()->flash('reputation_awarded', '+1 Reputation awarded for publishing a public paste!');

            // If user has a referrer, award 1 reputation to the referrer too!
            if ($user->referred_by) {
                $referrer = \App\Models\User::find($user->referred_by);
                if ($referrer) {
                    $referrerIdentification = \App\Models\Identification::firstOrCreate(
                        ['user_id' => $referrer->id],
                        ['reputation' => 0, 'role' => \App\Enum\Role::MEMBER->value]
                    );
                    $referrerIdentification->increment('reputation', 1);
                }
            }
        }

        // if has picture  ( arrray )
        if (isset($validateData['image'])) {
            foreach ($validateData['image'] as $image) {
                $imagePath = $image->store('uploadImages', 'public');
                $image = ImagePastebin::create([
                    'image_path' => $imagePath,
                    'pastebin_id' => $pastebin->id,
                ]);
            }
        }
        return $pastebin->slug;
    }

}
