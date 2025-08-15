<?php

namespace App\Console\Commands;

use App\Enums\DeliveryStatusEnum;
use App\Models\V1\Driver;
use App\Models\V1\Package;
use App\Models\V1\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $selects = [
            'deliveries.driver_id',
            DB::raw('COUNT(*) AS total_count'),
        ];

        $enumValues = [];
        foreach (DeliveryStatusEnum::cases() as $status) {
            $value = addslashes($status->value);
            $alias = strtolower($status->name) . '_count';
            $selects[] = DB::raw("SUM(CASE WHEN packages.status = '{$value}' THEN 1 ELSE 0 END) AS {$alias}");
            $enumValues[] = "'{$value}'";
        }

        $notInList = implode(',', $enumValues);
        $selects[] = DB::raw("SUM(CASE WHEN packages.status NOT IN ({$notInList}) THEN 1 ELSE 0 END) AS unknown_count");


        $notInList = implode(',', $enumValues);
        $selects[] = DB::raw("SUM(CASE WHEN packages.status NOT IN ({$notInList}) THEN 1 ELSE 0 END) AS unknown_count");

        $rows =  Package::query()
            ->join('deliveries', 'deliveries.id', '=', 'packages.delivery_id')
            ->select($selects)
            ->whereDate('packages.updated_at', now()->toDateString())
            ->groupBy('deliveries.driver_id')
            ->orderBy('deliveries.driver_id')
            ->get();

        foreach ($rows as $row) {
            Report::create([
                'driver_id' => $row->driver_id,
                'delivered' => $row->delivered_count,
                'returned' => $row->returned_count,
                'cancelled' => $row->cancelled_count,
                'failed' => $row->failed_count,
            ]);
        }
    }
}
