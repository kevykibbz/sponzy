<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\Traits\Functions;
use App\Models\LiveStreamingPrivateRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Enums\LiveStreamingPrivateStatus as Status;

class LiveStreamingPrivateExpired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Functions;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lives = LiveStreamingPrivateRequest::whereStatus(Status::PENDING->value)->get();

        foreach ($lives as $live) {            
            if (now() > $live->created_at->addDays(2)) {
                $this->refundLiveStreamRequest($live, Status::EXPIRED->value);
            }
        }
    }
}
