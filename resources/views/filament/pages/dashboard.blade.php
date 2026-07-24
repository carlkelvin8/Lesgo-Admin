<x-filament-panels::page>
    <!-- Welcome Banner -->
    <div class="rounded-xl bg-gradient-to-r from-purple-600 to-violet-500 p-6 text-white mb-6 shadow-lg">
        <h2 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}</h2>
        <p class="mt-1 opacity-90">Here's your logistics platform overview.</p>
    </div>

    <!-- Widgets -->
    <x-filament-widgets::widgets
        :widgets="$this->getWidgets()"
        :columns="$this->getColumns()"
    />
</x-filament-panels::page>

