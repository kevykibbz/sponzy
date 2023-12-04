<?php

namespace App\Jobs;

use App\Models\Advertising;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ExpiredAdvertising implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $advertisements = Advertising::select(['id'])
            ->where('expired_at', '<=', now())
            ->get();

        foreach ($advertisements as $advertising) {
            $advertising->update([
                'status' => 2
            ]);

            if (!config('settings.disable_new_post_notification')) {
                // Send notification New Post
                event(new NewPostEvent($post));
            }
        }
    }
}
