<?php

namespace App\Console\Commands;

use App\Services\DistributeFundsService;
use Illuminate\Console\Command;

class DistributeFundsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'funds:distribute {property_id} {amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute funds to investors of a property campaign.';

    /**
     * Execute the console command.
     */
    public function handle(DistributeFundsService $distributeFundsService): void
    {
        $property_id = $this->argument('property_id');
        $amount = (float)$this->argument('amount');

        $startTime = microtime(true);

        $result = $distributeFundsService->distribute($property_id, $amount);

        $this->info("Dividend of AED {$amount} distributed to {$result['count']} investors.");
        $this->info("Total distributed: AED {$result['total_distributed']}");
        $this->info("Average distributed per investor: AED {$result['distributed_amount_per_investor']}");

        $executionTime = microtime(true) - $startTime;
        $this->info("Execution time: " . number_format($executionTime, 2) . " seconds");
    }
}
