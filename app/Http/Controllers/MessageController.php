<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Events\PostCreate;
use App\Models\Message;
use App\Models\PatientMember;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class MessageController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $memberIds = [];
        if ($user->role === 'patient') {
            $memberIds = \App\Models\PatientMember::where('patient_id', $user->id)->pluck('id')->toArray();
        }

        $chats = \App\Models\Message::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->where('sender_type', $user->role);
        })->orWhere(function ($q) use ($user, $memberIds) {
            $q->where('receiver_id', $user->id)->where('receiver_type', $user->role);

            // Also include messages sent to this patient's members
            if (!empty($memberIds)) {
                $q->orWhere(function ($subQ) use ($memberIds) {
                    $subQ->whereIn('receiver_id', $memberIds)->where('receiver_type', 'patient_member');
                });
            }
        })->orderBy('created_at')->get();

        // All receivers
        $users = \App\Models\User::all()->map(function ($u) {
            $u->type = $u->role;
            return $u;
        });

        $members = \App\Models\PatientMember::all()->map(function ($m) {
            $m->type = 'patient_member';
            return $m;
        });

        $allReceivers = $users->concat($members);

        return view('chats', compact('chats', 'allReceivers'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'required|integer',
            'receiver_type' => 'required|string', // Now required
        ]);

        $user = auth()->user();

        $receiverId = $request->receiver_id;
        $receiverType = $request->receiver_type;

        // Extra validation: if patient replies as their member
        if ($user->role === 'patient' && $receiverType === 'doctor') {
            // optional: validate if they’re replying as their member
        }

        $senderType = $user->role;

        $message = \App\Models\Message::create([
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'receiver_id' => $receiverId,
            'receiver_type' => $receiverType,
            'message' => $request->message,
        ]);

        broadcast(new \App\Events\ChatEvent($message));

        return back()->with('success', 'Message sent successfully.');
    }
}
