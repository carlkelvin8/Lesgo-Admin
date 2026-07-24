<div class="grid grid-cols-3 gap-4">
    @php
        $record = $getRecord();
        $documents = [
            'Valid ID' => $record->id_document_path,
            'Clearance' => $record->clearance_document_path,
            "Driver's License" => $record->license_document_path,
            'Biodata' => $record->biodata_document_path,
            'Motor Registration' => $record->motor_registration_path,
            'Motor OR' => $record->motor_or_path,
        ];
    @endphp
    @foreach($documents as $type => $path)
        <div class="border rounded-lg p-3 bg-gray-50 dark:bg-gray-800">
            <p class="text-xs font-semibold mb-2 text-gray-600 dark:text-gray-300">{{ $type }}</p>
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
                <p class="text-sm text-gray-400 italic">Not uploaded</p>
            @endif
        </div>
    @endforeach
</div>
