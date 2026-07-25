<x-filament-panels::page>
    {{-- Welcome Section --}}
    <div class="rounded-xl bg-gradient-to-r from-purple-600 to-violet-500 p-8 text-white shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}</h2>
                <p class="mt-1 text-sm opacity-80">Here's your platform overview for today.</p>
            </div>
            <div class="hidden md:block text-right opacity-80">
                <p class="text-sm font-medium">{{ now()->format('l, F j, Y') }}</p>
                <p class="text-xs opacity-70">{{ now()->format('h:i A') }}</p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="mt-6">
        <x-filament-widgets::widgets
            :widgets="$this->getWidgets()"
            :columns="$this->getColumns()"
        />
    </div>

    {{-- Quick Links --}}
    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('filament.admin.resources.orders.index') }}" class="group flex items-center gap-3 rounded-lg border border-gray-200 dark:border-gray-700 p-4 transition hover:border-purple-300 hover:bg-purple-50 dark:hover:bg-purple-900/10">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600">
                <x-heroicon-o-shopping-bag class="h-5 w-5" />
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Orders</p>
                <p class="text-xs text-gray-500">View all</p>
            </div>
        </a>
        <a href="{{ route('filament.admin.resources.users.index') }}" class="group flex items-center gap-3 rounded-lg border border-gray-200 dark:border-gray-700 p-4 transition hover:border-purple-300 hover:bg-purple-50 dark:hover:bg-purple-900/10">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600">
                <x-heroicon-o-users class="h-5 w-5" />
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Users</p>
                <p class="text-xs text-gray-500">Manage</p>
            </div>
        </a>
        <a href="{{ route('filament.admin.resources.partners.index') }}" class="group flex items-center gap-3 rounded-lg border border-gray-200 dark:border-gray-700 p-4 transition hover:border-purple-300 hover:bg-purple-50 dark:hover:bg-purple-900/10">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600">
                <x-heroicon-o-building-office class="h-5 w-5" />
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Partners</p>
                <p class="text-xs text-gray-500">Merchants</p>
            </div>
        </a>
        <a href="{{ route('filament.admin.resources.driver-profiles.index') }}" class="group flex items-center gap-3 rounded-lg border border-gray-200 dark:border-gray-700 p-4 transition hover:border-purple-300 hover:bg-purple-50 dark:hover:bg-purple-900/10">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600">
                <x-heroicon-o-truck class="h-5 w-5" />
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Drivers</p>
                <p class="text-xs text-gray-500">Riders</p>
            </div>
        </a>
    </div>
</x-filament-panels::page>
