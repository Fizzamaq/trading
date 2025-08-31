<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProfitDistributionService;
use App\Services\NotificationService;
use Carbon\Carbon;

class DistributeMonthlyProfit extends Command
{
    protected $signature = 'profit:distribute {--month= : Month to distribute profit for (YYYY-MM format)}';
    protected $description = 'Distribute monthly profit among investors';

    protected $profitDistributionService;
    protected $notificationService;

    public function __construct(ProfitDistributionService $profitDistributionService, NotificationService $notificationService)
    {
        parent::__construct();
        $this->profitDistributionService = $profitDistributionService;
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $month = $this->option('month') ?: now()->subMonth()->format('Y-m-01');
        
        try {
            $this->info("Starting profit distribution for " . Carbon::parse($month)->format('F Y'));
            
            $monthlyProfit = $this->profitDistributionService->distributeMonthlyProfit($month);
            
            // Send notifications
            $this->notificationService->notifyProfitDistributionCompleted($monthlyProfit);
            
            $this->info("✅ Profit distribution completed successfully!");
            $this->info("Net Profit: PKR " . number_format($monthlyProfit->net_profit, 2));
            $this->info("Total Investors: " . $monthlyProfit->investorDistributions->count());
            
        } catch (\Exception $e) {
            $this->error("❌ Profit distribution failed: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
