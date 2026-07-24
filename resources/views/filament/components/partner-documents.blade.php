<div class="grid grid-cols-2 gap-4">
    @php $documents = $getRecord()->documents ?? []; @endphp
    @forelse($documents as $type => $path)
        <div class="border rounded-lg p-3 bg-gray-50 dark:bg-gray-800">
            <p class="text-xs font-semibold mb-2 capitalize text-gray-600 dark:text-gray-300">{{ str_replace('_', ' ', $type) }}</p>
            @if($path)
                @if(str_ends_with($path, '.pdf'))
                    <a href="{{ Storage::disk('s3')->url($path) }}" target="_blank" class="inline-flex items-center gap-1 text-primary-600 text-sm hover:underline">
                        📄 View PDF
                    </a>
                @else
                    <a href="{{ Storage::disk('s3')->url($path) }}" target="_blank">
                        <img src="{{ Storage::disk('s3')->url($path) }}" alt="{{ $type }}" class="w-40 h-40 object-contain rounded border bg-white">
                    </a>
                @endif
            @else
                <p class="text-sm text-gray-400">Not uploaded</p>
            @endif
        </div>
    @empty
        <p class="text-sm text-gray-400 col-span-2">No documents uploaded yet.</p>
    @endforelse
</div>
