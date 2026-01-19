<?php

namespace App\Events;

use App\Models\Code;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastPromoCodes implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels, InteractsWithBroadcasting;


    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        //
    }

    public function broadcastAs(): string
    {
        return 'promo-code-message';
    }


    public function broadcastWith()
    {

        $codes = Code::where('used', false)->where('expiry', '>', now())->get(['code', 'code_plan_id'])->toArray();

        return $codes;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('promo-code'),
        ];
    }
}
