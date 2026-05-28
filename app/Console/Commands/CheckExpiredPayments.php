<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:check-expired-payments')]
#[Description('Command description')]
class CheckExpiredPayments extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payments = \App\Models\Payment::with('order')
            ->where('status', 'pending')
            ->get();

        foreach ($payments as $payment) {

            if (
                $payment->order &&
                $payment->order->expired_at &&
                now()->greaterThan($payment->order->expired_at)
            ) {

                // UPDATE PAYMENT
                $payment->update([
                    'status' => 'expired'
                ]);

                // UPDATE ORDER
                $payment->order->update([
                    'status' => 'dibatalkan'
                ]);
            }
        }

        $this->info('Expired payments checked successfully.');
    }
}
