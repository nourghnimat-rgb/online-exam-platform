<?php

namespace App\Console\Commands;

use App\Services\AttemptService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('attempts:close-expired')]
#[Description('Close attempts that passed their deadline and queue them for grading')]
class CloseExpiredAttempts extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(AttemptService $service)
    {
        $count = $service->closeExpired();

        $this->info("Closed {$count} attempts.");
    }
}
