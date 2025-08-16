<?php

namespace App\Utilities;

use App\Enums\DeliveryStatusEnum;
use App\Models\V1\Driver;

class LeastLoadedDriver
{
    public function getDriverWithLeastPackages(): ?Driver
    {
        $inactiveStatuses = [
            DeliveryStatusEnum::DELIVERED->value,
            DeliveryStatusEnum::RETURNED->value,
        ];

        return Driver::withCount([
            'packages as packages_count' => fn ($q) => $q->whereNotIn('status', $inactiveStatuses),
            'packages as delivered_packages_count' => fn ($q) => $q->where('status', DeliveryStatusEnum::DELIVERED->value),
        ])/*->whereHas('packages', fn ($q) => $q->whereIn('status', $inactiveStatuses))*/
            ->orderBy('packages_count')
            ->orderByRaw('(CASE WHEN packages_count = 0 AND delivered_packages_count > 0 THEN 1 ELSE 0 END) ASC')
            ->orderBy('drivers.id')
            ->first();
    }
}
