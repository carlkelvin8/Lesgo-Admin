<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\DriverProfile;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $services = Service::where('is_active', true)->get();
        $drivers = DriverProfile::where('status', 'active')->get();

        $statuses = ['pending', 'accepted', 'picked_up', 'completed', 'cancelled'];
        $paymentMethods = ['cash', 'gcash', 'maya', 'card'];
        $paymentStatuses = ['pending', 'paid', 'failed'];

        // Create orders for the last 12 months
        for ($month = 11; $month >= 0; $month--) {
            $ordersThisMonth = rand(5, 20);

            for ($i = 0; $i < $ordersThisMonth; $i++) {
                $customer = $customers->random();
                $service = $services->random();
                $driver = $drivers->random();
                
                $addresses = Address::where('user_id', $customer->id)->get();
                $pickupAddress = $addresses->random();
                $dropoffAddress = $addresses->count() > 1 ? $addresses->where('id', '!=', $pickupAddress->id)->random() : $pickupAddress;

                $status = $statuses[array_rand($statuses)];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                
                // Completed orders are more likely to be paid
                $paymentStatus = $status === 'completed' 
                    ? (rand(0, 10) > 2 ? 'paid' : 'pending')
                    : ($status === 'cancelled' ? 'failed' : 'pending');

                $estimatedDistance = rand(5, 50) * 1000; // 5-50 km in meters
                $actualDistance = $status === 'completed' ? $estimatedDistance + rand(-2000, 2000) : null;
                
                $estimatedFare = $service->base_fare + ($estimatedDistance / 1000 * $service->per_km_rate);
                $actualFare = $actualDistance ? $service->base_fare + ($actualDistance / 1000 * $service->per_km_rate) : null;
                
                if ($actualFare && $actualFare < $service->minimum_fare) {
                    $actualFare = $service->minimum_fare;
                }

                $partnerShare = $actualFare ? $actualFare * 0.70 : null;
                $driverShare = $actualFare ? $actualFare * 0.20 : null;
                $platformFee = $actualFare ? $actualFare * 0.10 : null;

                $createdAt = now()->subMonths($month)->subDays(rand(0, 28))->subHours(rand(0, 23));

                Order::create([
                    'customer_id' => $customer->id,
                    'partner_id' => $service->partner_id,
                    'driver_id' => in_array($status, ['accepted', 'picked_up', 'completed']) ? $driver->id : null,
                    'service_id' => $service->id,
                    'pickup_address_id' => $pickupAddress->id,
                    'dropoff_address_id' => $dropoffAddress->id,
                    'status' => $status,
                    'scheduled_at' => rand(0, 1) ? $createdAt->copy()->addHours(rand(1, 48)) : null,
                    'accepted_at' => in_array($status, ['accepted', 'picked_up', 'completed']) ? $createdAt->copy()->addMinutes(rand(5, 30)) : null,
                    'picked_up_at' => in_array($status, ['picked_up', 'completed']) ? $createdAt->copy()->addMinutes(rand(30, 60)) : null,
                    'completed_at' => $status === 'completed' ? $createdAt->copy()->addMinutes(rand(60, 180)) : null,
                    'cancelled_at' => $status === 'cancelled' ? $createdAt->copy()->addMinutes(rand(5, 120)) : null,
                    'estimated_distance_m' => $estimatedDistance,
                    'actual_distance_m' => $actualDistance,
                    'estimated_fare' => $estimatedFare,
                    'actual_fare' => $actualFare,
                    'partner_share' => $partnerShare,
                    'driver_share' => $driverShare,
                    'platform_fee' => $platformFee,
                    'payment_method' => $paymentMethod,
                    'payment_status' => $paymentStatus,
                    'cancel_reason' => $status === 'cancelled' ? ['Customer request', 'Driver unavailable', 'Wrong address', 'Weather conditions'][array_rand(['Customer request', 'Driver unavailable', 'Wrong address', 'Weather conditions'])] : null,
                    'meta' => [
                        'notes' => 'Test order from seeder',
                        'priority' => rand(1, 5),
                    ],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}
