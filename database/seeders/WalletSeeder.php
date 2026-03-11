<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('role', ['customer', 'driver', 'partner'])->get();

        foreach ($users as $user) {
            $balance = rand(0, 10000);
            
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => $balance,
                'currency' => 'PHP',
            ]);

            // Create some transaction history
            if ($balance > 0) {
                $transactionCount = rand(1, 5);
                $currentBalance = 0;

                for ($i = 0; $i < $transactionCount; $i++) {
                    $amount = rand(100, 2000);
                    $type = ['credit', 'debit'][array_rand(['credit', 'debit'])];
                    
                    if ($type === 'credit') {
                        $balanceBefore = $currentBalance;
                        $currentBalance += $amount;
                        $description = 'Wallet top-up';
                    } else {
                        if ($currentBalance >= $amount) {
                            $balanceBefore = $currentBalance;
                            $currentBalance -= $amount;
                            $description = 'Payment for order';
                        } else {
                            continue;
                        }
                    }

                    WalletTransaction::create([
                        'wallet_id' => $wallet->id,
                        'type' => $type,
                        'source_type' => $type === 'credit' ? 'top_up' : 'order_payment',
                        'source_id' => rand(1, 100),
                        'amount' => $amount,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $currentBalance,
                        'description' => $description,
                        'created_by' => $user->id,
                        'created_at' => now()->subDays(rand(1, 30)),
                    ]);
                }

                // Update final balance
                $wallet->update(['balance' => $currentBalance]);
            }
        }
    }
}
