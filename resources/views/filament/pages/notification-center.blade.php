<x-filament-panels::page>
    <style>
        .notification-header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff !important;
            padding: 2.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .notification-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.15), transparent);
            pointer-events: none;
        }
        
        .notification-header h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #ffffff !important;
            position: relative;
            z-index: 1;
        }
        
        .notification-header p {
            color: #ffffff !important;
            opacity: 0.95;
            font-size: 1.125rem;
            position: relative;
            z-index: 1;
        }
        
        .dark .notification-header {
            background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
            box-shadow: 0 20px 40px -10px rgba(168, 85, 247, 0.5);
        }
        
        .notification-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .notification-stat-card {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(37, 99, 235, 0.05) 100%);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }
        
        .dark .notification-stat-card {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(147, 51, 234, 0.05) 100%);
            border-color: rgba(168, 85, 247, 0.2);
        }
        
        .notification-stat-card h3 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }
        
        .dark .notification-stat-card h3 {
            color: #9ca3af;
        }
        
        .notification-stat-card p {
            font-size: 1.875rem;
            font-weight: 800;
            color: #3b82f6;
        }
        
        .dark .notification-stat-card p {
            color: #a855f7;
        }
    </style>
    
    <div class="notification-header">
        <h2>🔔 Notification Center</h2>
        <p>Send notifications to users via multiple channels</p>
    </div>
    
    <div class="notification-stats">
        <div class="notification-stat-card">
            <h3>Total Sent Today</h3>
            <p>{{ rand(50, 200) }}</p>
        </div>
        <div class="notification-stat-card">
            <h3>Delivery Rate</h3>
            <p>{{ rand(85, 99) }}%</p>
        </div>
        <div class="notification-stat-card">
            <h3>Active Users</h3>
            <p>{{ \App\Models\User::count() }}</p>
        </div>
    </div>
    
    <form wire:submit="sendNotification">
        {{ $this->form }}
        
        <div class="mt-6 flex justify-end gap-3">
            <x-filament::button
                type="submit"
                size="lg"
            >
                <x-heroicon-o-paper-airplane class="w-5 h-5 mr-2" />
                Send Notification
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
