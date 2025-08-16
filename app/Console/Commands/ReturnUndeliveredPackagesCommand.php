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
    protected $description = 'Packages not delivered by the end of the day should be marked as returns and included in the daily reports for accountability.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Package::whereIn('status', [DeliveryStatusEnum::NEW, DeliveryStatusEnum::OUT_FOR_DELIVERY])
            ->update(['status' => DeliveryStatusEnum::RETURNED]);

        $this->generateReport();
    }

    private function generateReport(): void
    {
        Package::whereDate('scheduled_for', now())
            ->where('status', '!=', DeliveryStatusEnum::DELIVERED)
            ->update(['status' => DeliveryStatusEnum::RETURNED]);

        $today = now()->toDateString();

        Driver::with(['user:id,first_name,last_name'])->withCount([
            'packages as packages_returned_count' => fn ($q) => $q->whereDate('scheduled_for', $today)->where('status', DeliveryStatusEnum::RETURNED),
            'packages as delivered_packages_count' => fn ($q) => $q->whereDate('scheduled_for', $today)->where('status', DeliveryStatusEnum::DELIVERED),
        ])->orderBy('packages_returned_count', 'DESC')
            ->cursor()
            ->each(fn ($d) => Report::create([
                'driver_id' => $d->id,
                'packages_returned' => $d->packages_returned_count,
                'packages_delivered' => $d->delivered_packages_count,
            ]));


    }
}
