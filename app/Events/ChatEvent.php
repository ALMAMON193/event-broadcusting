<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        $channels = [];

        // Broadcast to sender's channel
        $channels[] = new PrivateChannel('chat.' . $this->message->sender_type . '.' . $this->message->sender_id);

        // Broadcast to receiver's channel
        $channels[] = new PrivateChannel('chat.' . $this->message->receiver_type . '.' . $this->message->receiver_id);

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'sender_type' => $this->message->sender_type,
            'receiver_id' => $this->message->receiver_id,
            'receiver_type' => $this->message->receiver_type,
            'message' => $this->message->message,
            'created_at' => $this->message->created_at->format('d M Y h:i A'),
            'timestamp' => $this->message->created_at->timestamp,
        ];
    }
}
