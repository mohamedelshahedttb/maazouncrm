<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Area;

class AreasIndex extends Component
{
    use WithPagination;
    public string $q = '';
    public string $status = '';
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public $transportation_fee = '';
    public $mahr_percentage = '';
    public bool $is_active = true;

    protected $updatesQueryString = ['q', 'status'];

    public function updatingQ() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function render()
    {
        $query = Area::query();
        if ($this->q !== '') {
            $query->where('name', 'like', "%{$this->q}%");
        }
        if ($this->status === 'active') $query->where('is_active', true);
        if ($this->status === 'inactive') $query->where('is_active', false);
        return view('livewire.areas-index', [
            'areas' => $query->orderBy('name')->paginate(15),
        ]);
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id)
    {
        $area = Area::findOrFail($id);
        $this->editingId = $area->id;
        $this->name = (string)$area->name;
        $this->transportation_fee = (string)$area->transportation_fee;
        $this->mahr_percentage = $area->mahr_percentage !== null ? (string)$area->mahr_percentage : '';
        $this->is_active = (bool)$area->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'transportation_fee' => 'required|numeric|min:0',
            'mahr_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        // Uniqueness manual check allowing same name for current editing
        $exists = Area::where('name', $this->name)
            ->when($this->editingId, fn($q) => $q->where('id', '!=', $this->editingId))
            ->exists();
        if ($exists) {
            $this->addError('name', 'الاسم مستخدم بالفعل');
            return;
        }

        if ($this->editingId) {
            Area::where('id', $this->editingId)->update([
                'name' => $this->name,
                'transportation_fee' => $this->transportation_fee,
                'mahr_percentage' => $this->mahr_percentage === '' ? null : $this->mahr_percentage,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('toast', type: 'success', message: 'تم تحديث المنطقة');
        } else {
            Area::create([
                'name' => $this->name,
                'transportation_fee' => $this->transportation_fee,
                'mahr_percentage' => $this->mahr_percentage === '' ? null : $this->mahr_percentage,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('toast', type: 'success', message: 'تم إنشاء المنطقة');
        }

        $this->showModal = false;
        $this->resetForm();
        $this->resetPage();
    }

    public function confirmDelete(int $id)
    {
        $area = Area::findOrFail($id);
        $area->delete();
        $this->dispatch('toast', type: 'success', message: 'تم حذف المنطقة');
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->transportation_fee = '';
        $this->mahr_percentage = '';
        $this->is_active = true;
    }
}
