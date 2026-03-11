<div class="space-y-3">
    @foreach($getState() as $activity)
        <div class="flex items-start gap-4 p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full flex items-center justify-center
                    @if($activity['color'] === 'success') bg-green-100 dark:bg-green-900/20
                    @elseif($activity['color'] === 'primary') bg-blue-100 dark:bg-blue-900/20
                    @elseif($activity['color'] === 'warning') bg-yellow-100 dark:bg-yellow-900/20
                    @else bg-gray-100 dark:bg-gray-900/20
                    @endif">
                    <x-dynamic-component 
                        :component="$activity['icon']" 
                        class="w-5 h-5
                            @if($activity['color'] === 'success') text-green-600 dark:text-green-400
                            @elseif($activity['color'] === 'primary') text-blue-600 dark:text-blue-400
                            @elseif($activity['color'] === 'warning') text-yellow-600 dark:text-yellow-400
                            @else text-gray-600 dark:text-gray-400
                            @endif"
                    />
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $activity['title'] }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $activity['description'] }}
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                    {{ $activity['timestamp']->diffForHumans() }}
                </p>
            </div>
        </div>
    @endforeach
</div>
