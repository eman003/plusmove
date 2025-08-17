<?php

namespace App\Console\Commands;

use App\Enums\DeliveryStatusEnum;
use App\Models\V1\Driver;
use App\Models\V1\Package;
use App\Models\V1\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReturnUndeliveredPackagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:return-packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Packages not delivered by the end of the day should be marked as returns and included in the daily reports.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->returnUndeliveredPackages();

        $this->generateReport();
    }

    /*
     * Get all packages that were scheduled for today and have not been delivered
     * and mark them as returned
     */
    private function returnUndeliveredPackages(): void
    {
        Package::whereDate('scheduled_for', now())
            ->where('status', '!=', DeliveryStatusEnum::DELIVERED)
            ->update(['status' => DeliveryStatusEnum::RETURNED]);
    }

    /*
     * Generate a daily report for each driver
     */
    private function generateReport(): void
    {
        $today = now()->toDateString();

        Driver::withCount([
            'packages as packages_returned_count' => fn ($q) => $q->whereDate('scheduled_for', $today)->where('status', DeliveryStatusEnum::RETURNED),
            'packages as delivered_packages_count' => fn ($q) => $q->whereDate('scheduled_for', $today)->where('status', DeliveryStatusEnum::DELIVERED),
        ])->cursor()
            ->each(fn ($d) => Report::create([
                'driver_id' => $d->id,
                'packages_returned' => $d->packages_returned_count,
                'packages_delivered' => $d->delivered_packages_count,
            ]));


    }
}
