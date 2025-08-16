<?php

namespace App\Observers;

use App\Enums\DeliveryStatusEnum;
use App\Events\MissingAddressEvent;
use App\Events\PackageDeliveryStatusEvent;
use App\Facades\AssignDriver;
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

        DB::transaction(function () use ($package) {
            $customer_address = $package->customer?->addresses;

            if ($customer_address->isNotEmpty())
            {
                $package->update([
                    'driver_id' => AssignDriver::getDriverWithLeastPackages()?->id,
                    'address_id' => $customer_address?->random()?->id,
                ]);
            }else{
                MissingAddressEvent::dispatch($package);
            }
        });

        PackageDeliveryStatusEvent::dispatch($package);
    }

    /**
     * Handle the Package "updated" event.
     */
    public function updated(Package $package): void
    {
        if ($package->wasChanged('status')){
            if ($package->status == DeliveryStatusEnum::DELIVERED->value)
                $package->update(['delivered_at' => now()]);

            PackageDeliveryStatusEvent::dispatch($package);
        }
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
