<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductionHealthCheck extends Command
{
    protected $signature = 'app:health-check';
    protected $description = 'Run production health checks on all tables and pages';

    public function handle(): int
    {
        $this->info('Running Production Health Check...');
        $this->newLine();

        $errors = 0;

        // 1. Database Connection
        $this->components->task('Database connection', function () {
            DB::connection()->getPdo();
            return true;
        });

        // 2. Check required tables exist
        $requiredTables = [
            'users', 'partners', 'driver_profiles', 'orders', 'payments',
            'services', 'vehicles', 'addresses', 'wallets', 'settings',
            'security_events', 'sessions', 'cache', 'jobs',
        ];

        foreach ($requiredTables as $table) {
            $exists = Schema::hasTable($table);
            $this->components->task("Table: {$table}", fn () => $exists);
            if (!$exists) $errors++;
        }

        // 3. Check admin user exists
        $this->components->task('Admin user exists', function () {
            return \App\Models\User::where('email', 'admin@lesgo.com')->exists();
        });

        // 4. Check key columns exist
        $columnChecks = [
            ['users', 'is_banned'],
            ['users', 'deleted_at'],
            ['driver_profiles', 'vehicle_type'],
            ['orders', 'driver_arrived_at'],
            ['partners', 'documents'],
        ];

        foreach ($columnChecks as [$table, $column]) {
            $exists = Schema::hasTable($table) && Schema::hasColumn($table, $column);
            $this->components->task("Column: {$table}.{$column}", fn () => $exists);
            if (!$exists) $errors++;
        }

        // 5. Count records
        $this->newLine();
        $this->info('Record counts:');
        $models = [
            'Users' => \App\Models\User::class,
            'Partners' => \App\Models\Partner::class,
            'Drivers' => \App\Models\DriverProfile::class,
            'Orders' => \App\Models\Order::class,
            'Payments' => \App\Models\Payment::class,
            'Services' => \App\Models\Service::class,
        ];

        foreach ($models as $label => $model) {
            try {
                $count = $model::count();
                $this->line("  {$label}: {$count}");
            } catch (\Exception $e) {
                $this->error("  {$label}: ERROR - " . $e->getMessage());
                $errors++;
            }
        }

        $this->newLine();
        if ($errors === 0) {
            $this->components->info('All checks passed!');
            return Command::SUCCESS;
        }

        $this->components->error("{$errors} check(s) failed.");
        return Command::FAILURE;
    }
}
