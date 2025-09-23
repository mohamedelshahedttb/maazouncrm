<?php

namespace App\Services;

use App\Models\Area;
use App\Models\Service;

class PricingService
{
    public function calculate(int $serviceId, ?int $areaId, ?float $mahr): float
    {
        $service = Service::findOrFail($serviceId);
        $total = 0.0;

        // Transportation fee + mahr percentage
        if ($areaId) {
            $area = Area::findOrFail($areaId);
            $total += (float)$area->transportation_fee;
            if ($mahr !== null && $area->mahr_percentage !== null) {
                $total += ($mahr * ((float)$area->mahr_percentage / 100.0));
            }
        }

        // Fixed fee by mahr range
        if ($mahr !== null) {
            $policy = $service->ratePolicies()
                ->where('is_active', true)
                ->where(function ($q) use ($mahr) {
                    $q->where(function ($q2) use ($mahr) {
                        $q2->whereNotNull('mahr_min')->where('mahr_min', '<=', $mahr);
                    })->orWhereNull('mahr_min');
                })
                ->where(function ($q) use ($mahr) {
                    $q->where(function ($q2) use ($mahr) {
                        $q2->whereNotNull('mahr_max')->where('mahr_max', '>=', $mahr);
                    })->orWhereNull('mahr_max');
                })
                ->orderByRaw('COALESCE(mahr_min, 0) DESC')
                ->first();

            if ($policy) {
                $total += (float)$policy->fixed_fee;
            }
        }

        return round($total, 2);
    }
}


