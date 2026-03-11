<x-filament-panels::page>
    <style>
        .reports-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            padding: 2.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px -10px rgba(16, 185, 129, 0.4);
        }
        
        .reports-header h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #ffffff !important;
        }
        
        .reports-header p {
            color: #ffffff !important;
            opacity: 0.95;
            font-size: 1.125rem;
        }
        
        .dark .reports-header {
            background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
        }
        
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .report-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .dark .report-card {
            background: linear-gradient(135deg, rgba(88, 28, 135, 0.3) 0%, rgba(76, 29, 149, 0.2) 100%);
            border-color: rgba(168, 85, 247, 0.2);
        }
        
        .report-card h3 {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #111827;
        }
        
        .dark .report-card h3 {
            color: #f3e8ff;
        }
        
        .report-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .dark .report-stat {
            border-bottom-color: rgba(168, 85, 247, 0.1);
        }
        
        .report-stat:last-child {
            border-bottom: none;
        }
        
        .report-stat-label {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .dark .report-stat-label {
            color: #e9d5ff;
        }
        
        .report-stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #10b981;
        }
        
        .dark .report-stat-value {
            color: #a855f7;
        }
    </style>
    
    <div class="reports-header">
        <h2>📊 Reports & Analytics</h2>
        <p>Comprehensive business insights and performance metrics</p>
    </div>
    
    @php
        $data = $this->getReportData();
    @endphp
    
    <div class="reports-grid">
        <div class="report-card">
            <h3>📦 Orders Overview</h3>
            <div class="report-stat">
                <span class="report-stat-label">Total Orders</span>
                <span class="report-stat-value">{{ number_format($data['orders']['total']) }}</span>
            </div>
            <div class="report-stat">
                <span class="report-stat-label">Today</span>
                <span class="report-stat-value">{{ number_format($data['orders']['today']) }}</span>
            </div>
            <div class="report-stat">
                <span class="report-stat-label">This Week</span>
                <span class="report-stat-value">{{ number_format($data['orders']['this_week']) }}</span>
            </div>
            <div class="report-stat">
                <span class="report-stat-label">This Month</span>
                <span class="report-stat-value">{{ number_format($data['orders']['this_month']) }}</span>
            </div>
        </div>
        
        <div class="report-card">
            <h3>💰 Revenue Overview</h3>
            <div class="report-stat">
                <span class="report-stat-label">Total Revenue</span>
                <span class="report-stat-value">₱{{ number_format($data['revenue']['total'], 2) }}</span>
            </div>
            <div class="report-stat">
                <span class="report-stat-label">Today</span>
                <span class="report-stat-value">₱{{ number_format($data['revenue']['today'], 2) }}</span>
            </div>
            <div class="report-stat">
                <span class="report-stat-label">This Week</span>
                <span class="report-stat-value">₱{{ number_format($data['revenue']['this_week'], 2) }}</span>
            </div>
            <div class="report-stat">
                <span class="report-stat-label">This Month</span>
                <span class="report-stat-value">₱{{ number_format($data['revenue']['this_month'], 2) }}</span>
            </div>
        </div>
        
        <div class="report-card">
            <h3>👥 Users Overview</h3>
            <div class="report-stat">
                <span class="report-stat-label">Total Users</span>
                <span class="report-stat-value">{{ number_format($data['users']['total']) }}</span>
            </div>
            <div class="report-stat">
                <span class="report-stat-label">Active Users</span>
                <span class="report-stat-value">{{ number_format($data['users']['active']) }}</span>
            </div>
            <div class="report-stat">
                <span class="report-stat-label">New This Month</span>
                <span class="report-stat-value">{{ number_format($data['users']['new_this_month']) }}</span>
            </div>
        </div>
        
        <div class="report-card">
            <h3>🚗 Drivers Overview</h3>
            <div class="report-stat">
                <span class="report-stat-label">Total Drivers</span>
                <span class="report-stat-value">{{ number_format($data['drivers']['total']) }}</span>
            </div>
            <div class="report-stat">
                <span class="report-stat-label">Active Drivers</span>
                <span class="report-stat-value">{{ number_format($data['drivers']['active']) }}</span>
            </div>
            <div class="report-stat">
                <span class="report-stat-label">Available Now</span>
                <span class="report-stat-value">{{ number_format($data['drivers']['available']) }}</span>
            </div>
        </div>
    </div>
</x-filament-panels::page>
