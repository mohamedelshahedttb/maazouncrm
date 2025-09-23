<div>
    <div class="flex items-center justify-between mb-4">
        <div></div>
        <button wire:click="openCreate" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">إضافة منطقة</button>
    </div>
    <div class="mb-4 bg-white border rounded p-4 grid grid-cols-1 md:grid-cols-5 gap-3">
        <div class="md:col-span-3">
            <input type="text" wire:model.debounce.400ms="q" placeholder="ابحث بالاسم" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <select wire:model="status" class="w-full border rounded px-3 py-2">
                <option value="">كل الحالات</option>
                <option value="active">مفعل</option>
                <option value="inactive">معطل</option>
            </select>
        </div>
    </div>

    <div class="bg-white border rounded shadow-sm">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">الاسم</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">رسوم المواصلات</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">نسبة المؤخر</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">الحالة</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($areas as $area)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $area->name }}</td>
                        <td class="px-4 py-3">{{ number_format($area->transportation_fee, 2) }}</td>
                        <td class="px-4 py-3">{{ $area->mahr_percentage !== null ? rtrim(rtrim(number_format($area->mahr_percentage, 2), '0'), '.') . '%' : '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded {{ $area->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">{{ $area->is_active ? 'مفعل' : 'معطل' }}</span>
                        </td>
                        <td class="px-4 py-3 text-left">
                            <button wire:click="openEdit({{ $area->id }})" class="px-3 py-1 text-blue-700 hover:text-blue-900">تعديل</button>
                            <button onclick="if(confirm('هل تريد حذف المنطقة {{ $area->name }}؟')) { Livewire.dispatch('delete-area', { id: {{ $area->id }} }); }" class="px-3 py-1 text-red-700 hover:text-red-900">حذف</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $areas->links() }}</div>
    </div>

    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded shadow w-full max-w-lg p-6">
            <h3 class="text-lg font-semibold mb-4">{{ $editingId ? 'تعديل منطقة' : 'إضافة منطقة' }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm mb-1">الاسم</label>
                    <input type="text" wire:model.defer="name" class="w-full border rounded px-3 py-2">
                    @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-sm mb-1">رسوم المواصلات</label>
                    <input type="number" step="0.01" min="0" wire:model.defer="transportation_fee" class="w-full border rounded px-3 py-2">
                    @error('transportation_fee')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-sm mb-1">نسبة المؤخر (٪) - اختياري</label>
                    <input type="number" step="0.01" min="0" max="100" wire:model.defer="mahr_percentage" class="w-full border rounded px-3 py-2">
                    @error('mahr_percentage')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model.defer="is_active">
                    <span>مفعل</span>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="$set('showModal', false)" class="px-4 py-2 border rounded">إلغاء</button>
                <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded">حفظ</button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('livewire:initialized', () => {
  Livewire.on('toast', (e) => {
    const el = document.createElement('div');
    el.className = 'fixed top-4 left-4 bg-green-50 border border-green-200 text-green-800 rounded px-4 py-2 shadow z-50';
    el.textContent = e.message || 'تم بنجاح';
    document.body.appendChild(el);
    setTimeout(()=>{ el.style.transition='opacity 300ms'; el.style.opacity='0'; setTimeout(()=>el.remove(), 350); }, 2000);
  });
  Livewire.on('delete-area', ({id}) => {
    Livewire.find(@this.__instance.id).call('confirmDelete', id);
  });
});
</script>
