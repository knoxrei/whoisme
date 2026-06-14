<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the chat room interface.
     */
    public function index()
    {
        return view('chat.index', [
            'title' => 'Global Chat Room'
        ]);
    }

    /**
     * Fetch all chat messages.
     */
    public function getMessages()
    {
        $messages = ChatMessage::with(['user.identification'])
            ->latest()
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'message' => $msg->message,
                'created_at' => $msg->created_at->toIso8601String(),
                'user' => [
                    'id' => $msg->user->id,
                    'username' => $msg->user->username,
                    'avatar_path' => $msg->user->identification->avatar_path ?? 'upload/defaultAvatar.png',
                    'role_label' => $msg->user->identification->role->label(),
                    'role_color' => $msg->user->identification->color_username ?? '#ffffff',
                ]
            ];
        }));
    }

    /**
     * Send a new chat message.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Broadcast to others in the channel
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'created_at' => $message->created_at->toIso8601String(),
                'user' => [
                    'id' => Auth::user()->id,
                    'username' => Auth::user()->username,
                    'avatar_path' => Auth::user()->identification->avatar_path ?? 'upload/defaultAvatar.png',
                    'role_label' => Auth::user()->identification->role->label(),
                    'role_color' => Auth::user()->identification->color_username ?? '#ffffff',
                ]
            ]
        ]);
    }
}
