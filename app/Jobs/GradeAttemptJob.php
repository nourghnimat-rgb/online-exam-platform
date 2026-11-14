<?php

namespace App\Jobs;

use App\Models\Attempt;
use App\Services\GradingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GradeAttemptJob implements ShouldQueue
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
    public function handle(GradingService $service): void
    {
        $service->grade(Attempt::findOrFail($this->attemptId));
    }
}
