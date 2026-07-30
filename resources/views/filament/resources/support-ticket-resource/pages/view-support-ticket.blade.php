<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Ticket Info Sidebar --}}
        <div class="lg:col-span-1 space-y-4">
            <x-filament::section>
                <x-slot name="heading">Ticket #{{ $record->ticket_number }}</x-slot>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <x-filament::badge>{{ ucwords(str_replace('_', ' ', $record->status)) }}</x-filament::badge>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Priority</span>
                        <x-filament::badge :color="match($record->priority) { 'urgent' => 'danger', 'high' => 'warning', 'medium' => 'info', default => 'gray' }">
                            {{ ucfirst($record->priority) }}
                        </x-filament::badge>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Category</span>
                        <span>{{ ucwords(str_replace('_', ' ', $record->category)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Customer</span>
                        <span>{{ $record->user?->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Assigned</span>
                        <span>{{ $record->assignee?->name ?? 'Unassigned' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Created</span>
                        <span>{{ $record->created_at->diffForHumans() }}</span>
                    </div>
                    @if($record->last_activity_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Last Activity</span>
                        <span>{{ $record->last_activity_at->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">Subject</x-slot>
                <p class="text-sm">{{ $record->subject }}</p>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">Description</x-slot>
                <p class="text-sm whitespace-pre-wrap">{{ $record->description }}</p>
            </x-filament::section>

            {{-- Quick Reply Form --}}
            <x-filament::section>
                <x-slot name="heading">Quick Reply</x-slot>
                <form wire:submit.prevent="sendQuickReply" class="space-y-3">
                    {{ $this->form }}
                    <x-filament::button type="submit" icon="heroicon-m-paper-airplane" class="w-full">
                        Send Reply
                    </x-filament::button>
                </form>
            </x-filament::section>
        </div>

        {{-- Chat Thread --}}
        <div class="lg:col-span-2">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center justify-between">
                        <span>Conversation</span>
                        <span class="text-sm text-gray-500">{{ $messages->count() }} messages</span>
                    </div>
                </x-slot>
                <div class="space-y-4 max-h-[600px] overflow-y-auto p-2">
                    @forelse($messages as $msg)
                        <div class="flex {{ $msg->is_internal ? 'justify-start' : ($msg->user_id === auth()->id() ? 'justify-end' : 'justify-start') }}">
                            <div class="max-w-[80%] {{ $msg->is_internal ? 'order-first' : '' }}">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold {{ $msg->is_internal ? 'text-warning-600' : ($msg->user_id === auth()->id() ? 'text-primary-600' : 'text-gray-600') }}">
                                        {{ $msg->is_internal ? 'Internal Note' : $msg->user?->name }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                                    @if($msg->is_internal)
                                        <x-filament::badge size="sm" color="warning">Internal</x-filament::badge>
                                    @endif
                                </div>
                                <div class="rounded-2xl px-4 py-2.5 text-sm {{ $msg->is_internal ? 'bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-700' : ($msg->user_id === auth()->id() ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-800') }}">
                                    <p class="whitespace-pre-wrap">{{ $msg->message }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400">
                            <x-heroicon-o-chat-bubble-left-right class="w-12 h-12 mx-auto mb-3 opacity-30" />
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    @endforelse
                </div>
            </x-filament::section>
        </div>
    </div>

    <style>
        .max-h-\[600px\]::-webkit-scrollbar { width: 6px; }
        .max-h-\[600px\]::-webkit-scrollbar-track { background: transparent; }
        .max-h-\[600px\]::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        .dark .max-h-\[600px\]::-webkit-scrollbar-thumb { background: rgba(168, 85, 247, 0.3); }
    </style>
</x-filament-panels::page>
