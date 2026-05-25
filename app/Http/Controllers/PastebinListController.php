<?php

namespace App\Http\Controllers;

use App\Models\Pastebin;
use Illuminate\Http\Request;

class PastebinListController extends Controller
{
    public function index()
    {
        $title = 'All Pastebins';
        
        $pastebins = Pastebin::where('visibility', 'public')
            ->whereNull('password')
            ->where('is_self_destruct', false)
            ->with(['user', 'user.identification'])
            ->latest()
            ->paginate(15);
            
        return view('pastebin.index', compact('title', 'pastebins'));
    }
}
