{{-- resources/views/components/filter-checkbox.blade.php --}}
<div class="flex flex-col mb-6">
    <p class="text-lg font-extrabold">{{ $title }}</p>
    @foreach ($items as $item)
        <label class="cursor-pointer mt-2">
            <input type="checkbox" value="{{ $item['id'] }}" {{ in_array($item['id'], $selectedItems) ? 'checked' : '' }}
            wire:model="{{ $filterModel }}" class="checkbox checkbox-xs [--chkbg:#C2DDFB] [--chkfg:#223F86]" />
            <span>{{ $item['name'] }}</span>
        </label>
    @endforeach
</div>
