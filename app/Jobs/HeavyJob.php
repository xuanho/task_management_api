<?php

namespace App\Jobs;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class HeavyJob implements ShouldQueue
{
    use Queueable;

    protected int $tries = 3;

    protected $backoff = [2, 5, 6];

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(2);
        Log::info('Heavy Job done');
        throw new Exception('test retry');
    }
}
