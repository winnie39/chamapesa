<?php

namespace App\Console\Commands;

use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\TradeWorker;
use App\Mail\OrderPaidMail;
use App\Models\Referral;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use function Laravel\Prompts\error;

class Corrections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:corrections';

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
        // Transaction::where('type', Transaction::WITHDRAWAL)->delete();
        // Transaction::where('type', Transaction::REFERRALS)->delete();
        $wallets = Wallet::get();

        foreach ($wallets as $item) {
            $item->update([
                'deposit' => 0
            ]);
        }
    }
}
