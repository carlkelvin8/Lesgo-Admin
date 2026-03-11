<x-filament-panels::page>
    <style>
        .bulk-operations-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff !important;
            padding: 2.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px -10px rgba(245, 158, 11, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .bulk-operations-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.15), transparent);
            pointer-events: none;
        }
        
        .bulk-operations-header h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #ffffff !important;
            position: relative;
            z-index: 1;
        }
        
        .bulk-operations-header p {
            color: #ffffff !important;
            opacity: 0.95;
            font-size: 1.125rem;
            position: relative;
            z-index: 1;
        }
        
        .dark .bulk-operations-header {
            background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
            box-shadow: 0 20px 40px -10px rgba(168, 85, 247, 0.5);
        }
    </style>
    
    <div class="bulk-operations-header">
        <h2>⚡ Bulk Operations</h2>
        <p>Perform actions on multiple items at once to save time</p>
    </div>
    
    <form wire:submit="assignDriver">
        {{ $this->form }}
        
        <div class="mt-6 flex justify-end gap-3">
            <x-filament::button
                type="submit"
                size="lg"
            >
                Execute Operation
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
