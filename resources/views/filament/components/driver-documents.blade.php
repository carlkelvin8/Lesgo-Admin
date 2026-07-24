<div class="grid grid-cols-2 gap-4">
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
        <div class="border rounded-lg p-4">
            <p class="text-sm font-semibold mb-2">{{ $type }}</p>
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
    @endforeach
</div>
