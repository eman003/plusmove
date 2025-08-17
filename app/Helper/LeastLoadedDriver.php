<?php

namespace App\Helper;

use App\Enums\DeliveryStatusEnum;
use App\Models\V1\Driver;

class LeastLoadedDriver
{
    /*
     * Get the driver with the least number of packages
     * and the least number of delivered packages
     * @return Driver|null
     */
    public function getDriverWithLeastPackages(): ?Driver
    {
        $inactiveStatuses = [
            DeliveryStatusEnum::DELIVERED->value,
            DeliveryStatusEnum::RETURNED->value,
        ];

        return Driver::withCount([
            'packages as packages_count' => fn ($q) => $q->whereNotIn('status', $inactiveStatuses),
            'packages as delivered_packages_count' => fn ($q) => $q->where('status', DeliveryStatusEnum::DELIVERED->value),
        ])
            ->orderBy('packages_count')
            ->orderByRaw('(CASE WHEN packages_count = 0 AND delivered_packages_count > 0 THEN 1 ELSE 0 END) ASC')
            ->orderBy('drivers.id')
            ->first();
    }
}
