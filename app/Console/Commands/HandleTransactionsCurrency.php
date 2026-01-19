<?php

namespace App\Console\Commands;

use App\Http\Controllers\Helpers\CountryHelper;
use App\Models\Transaction;
use Illuminate\Console\Command;

class HandleTransactionsCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:handle-transactions-currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::where('type', Transaction::WITHDRAWAL)->where('status', Transaction::COMPLETED)->get();
        $rates = CountryHelper::getRates();
        foreach ($transactions as $transaction) {
            $transaction->update([
                "amount" => $transaction['amount'] / $rates[$transaction['currency']],
                "amount_before_deduction" => $transaction['amount_before_deduction'] / $rates[$transaction['currency']],
                'currency' => config('app.currency')
            ]);
        }
    }
}
