<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessDistributionFundsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $investor, protected $amount)
    {
        //todo
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
    }
}
