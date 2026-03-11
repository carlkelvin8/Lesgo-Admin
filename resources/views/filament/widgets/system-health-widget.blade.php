<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-heart class="w-5 h-5 text-primary-500" />
                System Health Monitor
            </div>
        </x-slot>

        <x-slot name="description">
            Real-time system metrics and health indicators
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($this->getViewData()['metrics'] as $metric)
                <div class="flex items-center gap-4 p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center
                            @if($metric['status'] === 'success') bg-green-100 dark:bg-green-900/20
                            @elseif($metric['status'] === 'warning') bg-yellow-100 dark:bg-yellow-900/20
                            @elseif($metric['status'] === 'danger') bg-red-100 dark:bg-red-900/20
                            @else bg-blue-100 dark:bg-blue-900/20
                            @endif">
                            @svg($metric['icon'], 'w-6 h-6 ' . 
                                ($metric['status'] === 'success' ? 'text-green-600 dark:text-green-400' : 
                                ($metric['status'] === 'warning' ? 'text-yellow-600 dark:text-yellow-400' : 
                                ($metric['status'] === 'danger' ? 'text-red-600 dark:text-red-400' : 
                                'text-blue-600 dark:text-blue-400'))))
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                            {{ $metric['label'] }}
                        </p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $metric['value'] }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        @if($metric['status'] === 'success')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                Healthy
                            </span>
                        @elseif($metric['status'] === 'warning')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                Warning
                            </span>
                        @elseif($metric['status'] === 'danger')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                Critical
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                Info
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
