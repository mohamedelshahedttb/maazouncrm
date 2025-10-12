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

        // If a governorate is selected, its pricing rules are used INSTEAD of the base service price.
        if ($governorateId) {
            $gov = Governorate::find($governorateId);
            if ($gov) {
                $total += (float)$gov->base_fixed_fee;
                $total += (float)$gov->added_fees;
                if ($mahr !== null && $gov->mahr_percentage !== null && $gov->mahr_percentage > 0) {
                    $total += ($mahr * ((float)$gov->mahr_percentage / 100.0));
                }
            }
        } 
        // Otherwise, if no governorate is chosen, the standard service-based pricing applies.
        else if ($serviceId) {
            $service = Service::find($serviceId);
            if ($service) {
                // Start with the base service price
                $total += (float)$service->price;
                
                // Add fixed fee by mahr range from service rate policies
                if ($mahr !== null) {
                    $policy = $service->ratePolicies()
                        ->where('is_active', true)
                        ->where(function ($q) use ($mahr) {
                            $q->where('mahr_min', '<=', $mahr)->orWhereNull('mahr_min');
                        })
                        ->where(function ($q) use ($mahr) {
                            $q->where('mahr_max', '>=', $mahr)->orWhereNull('mahr_max');
                        })
                        ->orderByRaw('COALESCE(mahr_min, 0) DESC')
                        ->first();

                    if ($policy) {
                        $total += (float)$policy->fixed_fee;
                    }
                }
            }
        }

        // Area fees are always added on top, regardless of the above logic.
        if ($areaId) {
            $area = Area::find($areaId);
            if ($area) {
                $total += (float)$area->transportation_fee;
                if ($mahr !== null && $area->mahr_percentage !== null && $area->mahr_percentage > 0) {
                    $total += ($mahr * ((float)$area->mahr_percentage / 100.0));
                }
            }
        }
        
        return round($total, 2);
    }
}


