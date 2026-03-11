<?php

namespace Database\Seeders;

use App\Models\DriverProfile;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $drivers = DriverProfile::all();

        $vehicleTypes = ['sedan', 'suv', 'van', 'motorcycle', 'truck'];
        $brands = [
            'sedan' => ['Toyota', 'Honda', 'Mazda', 'Nissan'],
            'suv' => ['Toyota', 'Ford', 'Mitsubishi', 'Isuzu'],
            'van' => ['Toyota', 'Nissan', 'Hyundai', 'Mitsubishi'],
            'motorcycle' => ['Honda', 'Yamaha', 'Suzuki', 'Kawasaki'],
            'truck' => ['Isuzu', 'Mitsubishi', 'Hino', 'Fuso'],
        ];
        $models = [
            'sedan' => ['Vios', 'City', 'Civic', 'Corolla'],
            'suv' => ['Fortuner', 'Everest', 'Montero', 'MU-X'],
            'van' => ['Hiace', 'Urvan', 'Starex', 'L300'],
            'motorcycle' => ['TMX', 'Mio', 'Raider', 'Wave'],
            'truck' => ['Elf', 'Canter', 'Forward', 'Fighter'],
        ];
        $colors = ['White', 'Black', 'Silver', 'Red', 'Blue', 'Gray'];

        foreach ($drivers as $driver) {
            $type = $vehicleTypes[array_rand($vehicleTypes)];
            $brand = $brands[$type][array_rand($brands[$type])];
            $model = $models[$type][array_rand($models[$type])];

            Vehicle::create([
                'driver_id' => $driver->id,
                'partner_id' => $driver->partner_id,
                'type' => $type,
                'plate_number' => strtoupper(chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90))) . ' ' . rand(1000, 9999),
                'brand' => $brand,
                'model' => $model,
                'color' => $colors[array_rand($colors)],
                'year' => rand(2015, 2024),
                'is_primary' => true,
                'status' => ['active', 'active', 'active', 'maintenance'][array_rand(['active', 'active', 'active', 'maintenance'])],
            ]);

            // Some drivers have 2 vehicles
            if (rand(0, 3) === 0) {
                $type2 = $vehicleTypes[array_rand($vehicleTypes)];
                $brand2 = $brands[$type2][array_rand($brands[$type2])];
                $model2 = $models[$type2][array_rand($models[$type2])];

                Vehicle::create([
                    'driver_id' => $driver->id,
                    'partner_id' => $driver->partner_id,
                    'type' => $type2,
                    'plate_number' => strtoupper(chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90))) . ' ' . rand(1000, 9999),
                    'brand' => $brand2,
                    'model' => $model2,
                    'color' => $colors[array_rand($colors)],
                    'year' => rand(2015, 2024),
                    'is_primary' => false,
                    'status' => 'active',
                ]);
            }
        }
    }
}
