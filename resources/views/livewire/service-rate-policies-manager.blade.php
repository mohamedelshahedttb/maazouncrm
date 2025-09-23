<div class="mt-6">
    <h3 class="text-lg font-semibold mb-3">سياسات التسعير حسب المؤخر</h3>
    <div class="space-y-3">
        @foreach($rows as $i => $row)
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end bg-gray-50 p-4 rounded border">
            <div class="md:col-span-3">
                <label class="block text-sm mb-1">من مؤخر</label>
                <input type="number" step="0.01" wire:model.defer="rows.{{ $i }}.mahr_min" class="w-full border rounded px-3 py-2">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm mb-1">إلى مؤخر</label>
                <input type="number" step="0.01" wire:model.defer="rows.{{ $i }}.mahr_max" class="w-full border rounded px-3 py-2">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm mb-1">الرسوم الثابتة</label>
                <input type="number" step="0.01" min="0" wire:model.defer="rows.{{ $i }}.fixed_fee" class="w-full border rounded px-3 py-2">
                @error('rows.'.$i.'.fixed_fee')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="md:col-span-2 flex items-center gap-2 mt-6 md:mt-0">
                <input type="checkbox" wire:model.defer="rows.{{ $i }}.is_active">
                <span class="text-sm">مفعل</span>
            </div>
            <div class="md:col-span-1 text-left">
                <button type="button" wire:click="removeRow({{ $i }})" onclick="return confirm('حذف هذه السياسة؟')" class="px-3 py-2 border rounded text-red-600">حذف</button>
            </div>
        </div>
        @endforeach
    </div>
    <div class="flex gap-3 mt-3">
        <button type="button" wire:click="addRow" class="px-4 py-2 bg-green-600 text-white rounded">إضافة سياسة</button>
        <button type="button" wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded">حفظ السياسات</button>
    </div>
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
});
</script>

