<x-filament-panels::page>
    <style>
        /* Modern Dashboard Enhancements */
        .dashboard-container {
            max-width: 100%;
            margin: 0 auto;
        }
        
        /* Stats Overview Enhancements */
        .fi-wi-stats-overview .fi-wi-stats-overview-stat {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 16px;
            position: relative;
            overflow: hidden;
        }
        
        .fi-wi-stats-overview .fi-wi-stats-overview-stat::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .fi-wi-stats-overview .fi-wi-stats-overview-stat:hover::before {
            opacity: 1;
        }
        
        .fi-wi-stats-overview .fi-wi-stats-overview-stat:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -10px rgba(56, 189, 248, 0.25);
        }
        
        .dark .fi-wi-stats-overview .fi-wi-stats-overview-stat:hover {
            box-shadow: 0 20px 40px -10px rgba(168, 85, 247, 0.3);
        }
        
        /* Section Cards */
        .fi-section {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .fi-section:hover {
            box-shadow: 0 12px 28px -8px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }
        
        .dark .fi-section:hover {
            box-shadow: 0 12px 28px -8px rgba(168, 85, 247, 0.2);
        }
        
        /* Chart containers */
        .fi-wi-chart {
            border-radius: 16px;
            overflow: hidden;
            padding: 1.5rem;
        }
        
        /* Gradient background for stat values */
        .fi-wi-stats-overview-stat-value {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        
        /* Smooth animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fi-wi, .fi-section {
            animation: fadeInUp 0.6s ease-out backwards;
        }
        
        .fi-wi:nth-child(1), .fi-section:nth-child(1) { animation-delay: 0.05s; }
        .fi-wi:nth-child(2), .fi-section:nth-child(2) { animation-delay: 0.1s; }
        .fi-wi:nth-child(3), .fi-section:nth-child(3) { animation-delay: 0.15s; }
        .fi-wi:nth-child(4), .fi-section:nth-child(4) { animation-delay: 0.2s; }
        .fi-wi:nth-child(5), .fi-section:nth-child(5) { animation-delay: 0.25s; }
        .fi-wi:nth-child(6), .fi-section:nth-child(6) { animation-delay: 0.3s; }
        .fi-wi:nth-child(7), .fi-section:nth-child(7) { animation-delay: 0.35s; }
        .fi-wi:nth-child(8), .fi-section:nth-child(8) { animation-delay: 0.4s; }
        
        /* Enhanced table styling */
        .fi-ta-table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .fi-ta-row {
            transition: all 0.2s ease;
        }
        
        .fi-ta-row:hover {
            background: linear-gradient(90deg, rgba(56, 189, 248, 0.08) 0%, transparent 100%);
            transform: translateX(4px);
        }
        
        .dark .fi-ta-row:hover {
            background: linear-gradient(90deg, rgba(168, 85, 247, 0.1) 0%, transparent 100%);
        }
        
        /* Custom scrollbar */
        .fi-ta-content::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        .fi-ta-content::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 5px;
        }
        
        .fi-ta-content::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 5px;
        }
        
        .fi-ta-content::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2, #667eea);
        }
        
        .dark .fi-ta-content::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #7e22ce, #9333ea);
        }
        
        /* Badge enhancements */
        .fi-badge {
            font-weight: 600;
            letter-spacing: 0.025em;
            transition: all 0.2s ease;
            border-radius: 9999px;
            padding: 0.375rem 0.875rem;
        }
        
        .fi-badge:hover {
            transform: scale(1.05);
        }
        
        /* Chart canvas styling */
        canvas {
            border-radius: 12px;
        }
        
        /* Widget spacing */
        .fi-wi {
            margin-bottom: 1.5rem;
        }
        
        /* Loading state */
        .fi-section.fi-section-loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        /* Icon enhancements */
        .fi-icon {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .fi-section:hover .fi-icon,
        .fi-wi-stats-overview-stat:hover .fi-icon {
            transform: scale(1.15) rotate(5deg);
        }
        
        /* Stat card improvements */
        .fi-wi-stats-overview-stat-chart {
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .fi-wi-stats-overview-stat:hover .fi-wi-stats-overview-stat-chart {
            opacity: 1;
        }
        
        /* Dashboard welcome banner */
        .dashboard-welcome {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 2.5rem;
            box-shadow: 0 20px 50px -15px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-welcome::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.15), transparent);
            pointer-events: none;
        }
        
        .dashboard-welcome-content {
            position: relative;
            z-index: 1;
        }
        
        .dashboard-welcome h2 {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            color: #ffffff !important;
            letter-spacing: -0.025em;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-welcome p {
            opacity: 0.95;
            font-size: 1.125rem;
            color: #ffffff !important;
            font-weight: 500;
        }
        
        .dashboard-welcome-icon {
            font-size: 3rem;
            display: inline-block;
            animation: wave 2s ease-in-out infinite;
        }
        
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(20deg); }
            75% { transform: rotate(-20deg); }
        }
        
        .dark .dashboard-welcome {
            background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
            box-shadow: 0 20px 50px -15px rgba(168, 85, 247, 0.5);
        }
        
        .dark .dashboard-welcome h2,
        .dark .dashboard-welcome p {
            color: #ffffff !important;
        }
        
        /* Section headers */
        .fi-section-header-heading {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        
        /* Grid improvements */
        .fi-wi-stats-overview {
            gap: 1.5rem;
        }
        
        /* Responsive improvements */
        @media (min-width: 1024px) {
            .fi-section {
                min-height: 100%;
            }
            
            .dashboard-welcome {
                padding: 3rem;
            }
            
            .dashboard-welcome h2 {
                font-size: 2.5rem;
            }
        }
        
        /* Quick stats highlight */
        .fi-wi-stats-overview-stat:nth-child(1) {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        }
        
        .dark .fi-wi-stats-overview-stat:nth-child(1) {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.15) 0%, rgba(147, 51, 234, 0.1) 100%);
        }
        
        /* Table header enhancement */
        .fi-ta-header {
            font-weight: 700;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        
        /* Action buttons */
        .fi-ta-actions {
            gap: 0.5rem;
        }
        
        .fi-ta-action-btn {
            transition: all 0.2s ease;
        }
        
        .fi-ta-action-btn:hover {
            transform: scale(1.1);
        }
    </style>
    
    <!-- Welcome Banner -->
    <div class="dashboard-welcome">
        <div class="dashboard-welcome-content">
            <h2>
                <span class="dashboard-welcome-icon">👋</span>
                Welcome back, {{ auth()->user()->name }}!
            </h2>
            <p>Here's what's happening with your logistics platform today.</p>
        </div>
    </div>
    
    <div class="dashboard-container">
        <!-- Widgets Grid -->
        <x-filament-widgets::widgets
            :widgets="$this->getWidgets()"
            :columns="$this->getColumns()"
        />
    </div>
</x-filament-panels::page>
