<x-filament-panels::page>
    <style>
        /* Settings Page Styles */
        .settings-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #ffffff !important;
            padding: 2.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .settings-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.1), transparent);
            pointer-events: none;
        }
        
        .dark .settings-header {
            background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
            box-shadow: 0 20px 40px -10px rgba(168, 85, 247, 0.5);
        }
        
        .settings-header h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            letter-spacing: -0.025em;
        }
        
        .settings-header p {
            color: #ffffff !important;
            opacity: 0.95;
            font-size: 1.125rem;
            font-weight: 500;
        }
        
        .settings-header .last-updated {
            color: #ffffff !important;
        }
        
        .settings-header .last-updated-label {
            color: rgba(255, 255, 255, 0.85) !important;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .settings-header .last-updated-value {
            color: #ffffff !important;
            font-size: 1.125rem;
            font-weight: 700;
        }
        
        /* Tab styling enhancements */
        .fi-tabs {
            margin-bottom: 1.5rem;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .fi-tabs-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
        }
        
        .fi-tabs-item:hover {
            transform: translateY(-2px);
            background-color: rgba(99, 102, 241, 0.1);
        }
        
        .dark .fi-tabs-item:hover {
            background-color: rgba(168, 85, 247, 0.1);
        }
        
        .fi-tabs-item[aria-selected="true"] {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        
        .dark .fi-tabs-item[aria-selected="true"] {
            background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.4);
        }
        
        /* Section enhancements */
        .fi-section {
            margin-bottom: 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .fi-section:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .dark .fi-section:hover {
            box-shadow: 0 8px 20px rgba(168, 85, 247, 0.2);
        }
        
        .fi-section-header-heading {
            font-weight: 700;
            font-size: 1.125rem;
        }
        
        /* Form field spacing */
        .fi-fo-field-wrp {
            margin-bottom: 1.25rem;
        }
        
        /* Input enhancements */
        .fi-input:focus,
        .fi-select:focus,
        .fi-textarea:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .dark .fi-input:focus,
        .dark .fi-select:focus,
        .dark .fi-textarea:focus {
            border-color: #a855f7 !important;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.2);
        }
        
        /* Toggle switch enhancements */
        .fi-toggle {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .fi-toggle:hover {
            transform: scale(1.05);
        }
        
        /* Badge enhancements */
        .fi-badge {
            font-weight: 600;
            border-radius: 9999px;
            padding: 0.375rem 0.875rem;
        }
        
        /* Helper text styling */
        .fi-fo-field-wrp-hint {
            font-size: 0.875rem;
            opacity: 0.75;
        }
        
        /* Save button enhancement */
        .fi-btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%) !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 700;
            padding: 0.875rem 2rem;
            border-radius: 12px;
        }
        
        .dark .fi-btn-primary {
            background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%) !important;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.4);
        }
        
        .fi-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(99, 102, 241, 0.4);
        }
        
        .dark .fi-btn-primary:hover {
            box-shadow: 0 12px 24px rgba(168, 85, 247, 0.5);
        }
        
        /* Icon animations */
        .settings-icon {
            display: inline-block;
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Smooth page transitions */
        .fi-main {
            animation: fadeInUp 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    <!-- Settings Header -->
    <div class="settings-header">
        <div class="flex items-center justify-between">
            <div>
                <h2><span class="settings-icon">⚙️</span> Application Settings</h2>
                <p>Configure your application preferences and system settings</p>
            </div>
            <div class="text-right last-updated">
                <div class="last-updated-label">Last Updated</div>
                <div class="last-updated-value">{{ now()->format('M d, Y H:i') }}</div>
            </div>
        </div>
    </div>
    
    <!-- Settings Form -->
    <form wire:submit="save">
        {{ $this->form }}
        
        <div class="mt-6 flex justify-end gap-3">
            <x-filament::button
                type="submit"
                size="lg"
            >
                <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
                Save Settings
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
