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

        $chats = Message::where(function($q) use ($user) {
            $q->where('sender_id', $user->id)->where('sender_type', $user->role);
        })->orWhere(function($q) use ($user) {
            $q->where('receiver_id', $user->id)->where('receiver_type', $user->role);
        })->orderBy('created_at')->get();

        $users = User::all()->map(function ($u) {
            $u->type = $u->role;
            return $u;
        });

        $members = PatientMember::all()->map(function ($m) {
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
        ]);

        $user = auth()->user();

        $receiverId = $request->receiver_id;

        // Detect receiver_type by searching in User and PatientMember tables
        $receiverType = null;
        $receiverUser = \App\Models\User::find($receiverId);
        if ($receiverUser) {
            $receiverType = $receiverUser->role; // e.g. 'doctor' or 'patient'
        } else {
            $receiverMember = \App\Models\PatientMember::find($receiverId);
            if ($receiverMember) {
                $receiverType = 'patient_member';
            } else {
                return back()->withErrors('Receiver not found');
            }
        }

        // Your sender_type from logged in user role (make sure role is set)
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
