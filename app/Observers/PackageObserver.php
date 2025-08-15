<?php

namespace App\Observers;

use App\Enums\DeliveryStatusEnum;
use App\Events\PackageDeliveryStatus;
use App\Models\V1\Delivery;
use App\Models\V1\Driver;
use App\Models\V1\Package;
use Illuminate\Support\Facades\DB;

class PackageObserver
{
    /**
     * Handle the Package "created" event.
     */
    public function created(Package $package): void
    {

        DB::transaction(function () use ($package,) {
            $inactiveStatuses = [
                DeliveryStatusEnum::DELIVERED->value,
                DeliveryStatusEnum::CANCELLED->value,
                DeliveryStatusEnum::RETURNED->value,
                DeliveryStatusEnum::FAILED->value,
            ];

            // Subquery: pick one open delivery for each driver (deterministic order)
            $openDeliveryIdSub = Delivery::query()
                ->select('id')
                ->whereColumn('deliveries.driver_id', 'drivers.id')
                ->orderBy('id')
                ->limit(1);

            // Main query: least active packages + include open_delivery_id
            $driverRow = Driver::query()
                ->select('drivers.id')
                ->selectSub($openDeliveryIdSub, 'open_delivery_id')
                ->withCount([
                    'packages as active_packages_count' => fn ($q) =>
                    $q->whereNotIn('status', $inactiveStatuses),
                ])
                ->orderBy('active_packages_count')
                ->orderBy('drivers.id')
                ->lockForUpdate() // avoid race when multiple packages are created concurrently
                ->first();

            if (! $driverRow) {
                // No drivers available; nothing to assign
                return;
            }

            $openDeliveryId = $driverRow->open_delivery_id;

            if (! $openDeliveryId) {
                // Create a new open delivery for this driver
                $newDelivery = Delivery::create([
                    'driver_id' => $driverRow->id,
                ]);
                $openDeliveryId = $newDelivery->id;
            }

            $package->update([
                'delivery_id' => $openDeliveryId,
                'address_id' => $package->customer?->addresses?->random()?->id,
            ]);
        });

    }

    /**
     * Handle the Package "updated" event.
     */
    public function updated(Package $package): void
    {
        PackageDeliveryStatus::dispatch($package);
    }

    /**
     * Handle the Package "deleted" event.
     */
    public function deleted(Package $package): void
    {
        //
    }

    /**
     * Handle the Package "restored" event.
     */
    public function restored(Package $package): void
    {
        //
    }

    /**
     * Handle the Package "force deleted" event.
     */
    public function forceDeleted(Package $package): void
    {
        //
    }
}
