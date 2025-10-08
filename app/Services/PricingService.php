<?php

namespace App\Services;

use App\Models\Area;
use App\Models\Service;
use App\Models\Governorate;

class PricingService
{
    public function calculate(?int $serviceId, ?int $areaId, ?float $mahr, ?int $governorateId = null): float
    {
        $total = 0.0;

        // Governorate-based pricing (if provided): base_fixed_fee + added_fees + mahr percentage (if set)
        if ($governorateId) {
            $gov = Governorate::findOrFail($governorateId);
            $total += (float)$gov->base_fixed_fee;
            $total += (float)$gov->added_fees;
            if ($mahr !== null && $gov->mahr_percentage !== null) {
                $total += ($mahr * ((float)$gov->mahr_percentage / 100.0));
            }
        }

        // Area-based pricing (ignored when governorate is selected)
        if ($areaId && !$governorateId) {
            $area = Area::findOrFail($areaId);
            $total += (float)$area->transportation_fee;
            if ($mahr !== null && $area->mahr_percentage !== null) {
                $total += ($mahr * ((float)$area->mahr_percentage / 100.0));
            }
        }

        // Fixed fee by mahr range (only when a service is provided)
        if ($serviceId && $mahr !== null) {
            $service = Service::findOrFail($serviceId);
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


