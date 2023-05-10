<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Repayment;
use Illuminate\Console\Command;

class CheckRepaymentsOverdueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repayments:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find all overdue repayments which are "pending", and update their statuses to "overdue"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $todayDate = Carbon::now()->toDateString();
        $repaymentsCount = Repayment::query()
            ->where('status', Repayment::STATUS_PENDING)
            ->where('due_date', '<=', $todayDate)
            ->update(['status' => Repayment::STATUS_OVERDUE]);

        $this->info("$todayDate: number of overdue repayments is $repaymentsCount");
    }
}
