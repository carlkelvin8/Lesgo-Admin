<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::whereNotNull('actual_fare')->get();

        foreach ($orders as $order) {
            // Only create payment if order has actual fare
            if ($order->actual_fare) {
                $status = $order->payment_status;
                
                Payment::create([
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'partner_id' => $order->partner_id,
                    'driver_id' => $order->driver_id,
                    'amount' => $order->actual_fare,
                    'currency' => 'PHP',
                    'method' => $order->payment_method,
                    'status' => $status,
                    'provider' => in_array($order->payment_method, ['gcash', 'maya', 'card']) 
                        ? ucfirst($order->payment_method) . ' Payment Gateway' 
                        : null,
                    'provider_reference' => in_array($order->payment_method, ['gcash', 'maya', 'card']) 
                        ? strtoupper($order->payment_method) . '-' . now()->format('Ymd') . '-' . rand(100000, 999999)
                        : null,
                    'paid_at' => $status === 'paid' ? $order->completed_at : null,
                    'meta' => [
                        'payment_gateway' => $order->payment_method,
                        'transaction_fee' => $order->actual_fare * 0.025,
                    ],
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);
            }
        }
    }
}
