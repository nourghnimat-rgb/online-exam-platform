<?php

namespace App\Jobs;

use App\Models\Attempt;
use App\Notifications\ResultReady;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendResultNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $attemptId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $attempt = Attempt::findOrFail($this->attemptId);

        $attempt->student->notify(new ResultReady($attempt->exam->title, $attempt->id));
    }
}
