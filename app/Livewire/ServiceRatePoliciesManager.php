<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Service;
use App\Models\ServiceRatePolicy;

class ServiceRatePoliciesManager extends Component
{
    public int $serviceId;
    public array $rows = [];

    public function mount(int $serviceId)
    {
        $this->serviceId = $serviceId;
        $service = Service::with('ratePolicies')->findOrFail($serviceId);
        $this->rows = $service->ratePolicies->map(function($p){
            return [
                'id' => $p->id,
                'mahr_min' => $p->mahr_min,
                'mahr_max' => $p->mahr_max,
                'fixed_fee' => $p->fixed_fee,
                'is_active' => (bool)$p->is_active,
            ];
        })->toArray();
    }

    public function addRow()
    {
        $this->rows[] = [
            'id' => null,
            'mahr_min' => null,
            'mahr_max' => null,
            'fixed_fee' => null,
            'is_active' => true,
        ];
    }

    public function removeRow(int $index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    public function save()
    {
        $validated = [];
        foreach ($this->rows as $i => $row) {
            $validated[$i] = $this->validateRow($row, $i);
        }

        $service = Service::findOrFail($this->serviceId);

        $keepIds = [];
        foreach ($validated as $i => $data) {
            if (!empty($this->rows[$i]['id'])) {
                $policy = ServiceRatePolicy::where('service_id', $service->id)->find($this->rows[$i]['id']);
                if ($policy) {
                    $policy->update($data);
                    $keepIds[] = $policy->id;
                }
            } else {
                $new = $service->ratePolicies()->create($data);
                $keepIds[] = $new->id;
                $this->rows[$i]['id'] = $new->id;
            }
        }

        ServiceRatePolicy::where('service_id', $service->id)
            ->when(!empty($keepIds), fn($q) => $q->whereNotIn('id', $keepIds))
            ->delete();

        $this->dispatch('toast', type: 'success', message: 'تم حفظ سياسات التسعير');
    }

    private function validateRow(array $row, int $i): array
    {
        $this->resetErrorBag();
        $min = $row['mahr_min'];
        $max = $row['mahr_max'];
        $fixed = $row['fixed_fee'];
        $active = (bool)($row['is_active'] ?? true);

        if ($fixed === null || $fixed === '') {
            $this->addError("rows.$i.fixed_fee", 'الرسوم الثابتة مطلوبة');
        }
        if ($this->getErrorBag()->isNotEmpty()) {
            $this->dispatch('toast', type: 'error', message: 'تحقق من الحقول');
        }

        return [
            'mahr_min' => $min !== '' ? $min : null,
            'mahr_max' => $max !== '' ? $max : null,
            'fixed_fee' => $fixed,
            'is_active' => $active,
        ];
    }

    public function render()
    {
        return view('livewire.service-rate-policies-manager');
    }
}


