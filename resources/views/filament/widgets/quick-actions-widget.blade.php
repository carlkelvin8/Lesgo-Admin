<x-filament-widgets::widget>
    <x-filament::section>
        <div class="quick-actions-container">
            <div class="quick-actions-header">
                <h3 class="text-lg font-bold">⚡ Quick Actions</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Frequently used actions for faster workflow</p>
            </div>
            
            <div class="quick-actions-grid">
                @foreach($this->getActions() as $action)
                    <a href="{{ $action['url'] }}" 
                       class="quick-action-card quick-action-{{ $action['color'] }}">
                        <div class="quick-action-icon">
                            <x-filament::icon 
                                :icon="$action['icon']"
                                class="w-8 h-8"
                            />
                        </div>
                        <span class="quick-action-label">{{ $action['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </x-filament::section>

    <style>
        .quick-actions-container {
            padding: 0.5rem;
        }
        
        .quick-actions-header {
            margin-bottom: 1.5rem;
        }
        
        .quick-actions-header h3 {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        
        .quick-action-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            border: 2px solid transparent;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        }
        
        .dark .quick-action-card {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(147, 51, 234, 0.05) 100%);
        }
        
        .quick-action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(99, 102, 241, 0.3);
            border-color: rgba(99, 102, 241, 0.3);
        }
        
        .dark .quick-action-card:hover {
            box-shadow: 0 12px 24px -8px rgba(168, 85, 247, 0.4);
            border-color: rgba(168, 85, 247, 0.3);
        }
        
        .quick-action-icon {
            margin-bottom: 0.75rem;
            padding: 1rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transition: all 0.3s ease;
        }
        
        .dark .quick-action-icon {
            background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
        }
        
        .quick-action-card:hover .quick-action-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .quick-action-label {
            font-weight: 600;
            font-size: 0.875rem;
            text-align: center;
            color: #374151;
        }
        
        .dark .quick-action-label {
            color: #e9d5ff;
        }
        
        @media (max-width: 640px) {
            .quick-actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</x-filament-widgets::widget>
