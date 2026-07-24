<div class="grid grid-cols-2 gap-4">
    @php $documents = $getRecord()->documents ?? []; @endphp
    @forelse($documents as $type => $path)
        <div class="border rounded-lg p-4">
            <p class="text-sm font-semibold mb-2 capitalize">{{ str_replace('_', ' ', $type) }}</p>
            @if($path)
                @if(str_ends_with($path, '.pdf'))
                    <a href="{{ Storage::disk('s3')->url($path) }}" target="_blank" class="text-primary-600 underline text-sm">
                        📄 View PDF
                    </a>
                @else
                    <img src="{{ Storage::disk('s3')->url($path) }}" alt="{{ $type }}" class="w-full h-32 object-cover rounded">
                @endif
            @else
                <p class="text-sm text-gray-400">Not uploaded</p>
            @endif
        </div>
    @empty
        <p class="text-sm text-gray-400 col-span-2">No documents uploaded yet.</p>
    @endforelse
</div>
