<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresenceChannelEvent implements ShouldBroadcast
{
use Dispatchable, InteractsWithSockets, SerializesModels;
    protected string $message = 'Hello World!';
    public function __construct(public $user) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel(name: "presence-channel")];
    }

    public function broadcastAs(): string
    {
        return 'presence-channel-event';
    }

    public function broadcastWith(): array
    {
        return [
            "message" => $this->message
        ];
    }
}
