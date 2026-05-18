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
            $user->identification()->increment('reputation', 1);
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
