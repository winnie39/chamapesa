<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\CelcomHelper;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\TradeSettingHelper;
use App\Models\PaymentMethod;
use App\Models\TradeSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInvestmentPlan;
use App\Models\Wallet;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class WithdrawController extends Controller
{
    public function index()
    {

        $rates = CountryHelper::getRates();
        // $rates = 1;
        $data['methods'] = PaymentMethod::get()->map(function ($value) use ($rates) {

            [$charge['percentage'], $charge['flatFee']] = explode('%', $value['rate']);

            return [

                'name' => $value['name'],
                'phone' => $value['phone'],
                'description' => $value['description'],
                'rate' => $value['rate'],
                'currency' => $value['currency'],
                'parameter' => $value['parameter'],
                'conversion' => $rates[$value['currency']],
                'id' => $value['id'],
                'percentage' => $charge['percentage'],
                'flatFee' => $charge['flatFee'],

            ];
        });

        [$data['percentage'], $data['flatFee']] = explode('%', env('WITHDRAW_FEE', '0.1%0'));

        return view('pages.withdraw', compact('data'));
    }

    public function withdraw(Request $request)
    {

        $allowedPaymentMethods = PaymentMethod::get()->pluck('id');
        $allowedWallets = ['profits', 'bonus'];

        $accountBalance = $this->accountBalance($request->wallet);

        $currency = config('app.currency');
        $request->validate([
            'amount' => "required|numeric|min:1|max:{$accountBalance}",
            'method' => ['required', Rule::in($allowedPaymentMethods)],
            'address' => 'required',
        ], [
            'address.required' => 'This field is required',
            'method.required' => 'The payment method field is required',
            'method.in' => 'Invalid payment method',
            'amount.max' => "Your balance is under {$request->amount} {$currency}",
        ]);


        if ($request->amount > $accountBalance) {
            Toastr::error('Your balance is insuficient');
            return back();
        }


        $transaction = Transaction::where('user_id', auth()->id())->first();
        if (!$transaction) {
            Toastr::error('Your balance is insuficient');
            return back();
        }

        $user = User::find(auth()->id());



        $dateToday = Carbon::parse(now())->hour(0)->minute(0)->second(0);

        $withdrawalsToday = Transaction::where('user_id', auth()->id())->where('created_at', '>', $dateToday)->where('type', Transaction::WITHDRAWAL)->get()->count();

        if ($withdrawalsToday > 10 && !in_array(auth()->user()->email, config('app.admins'))) {
            Toastr::error('Apologies, but only ten withdrawals are permitted within a 24-hour period.');
            return back();
        }


        if ($user->transactions()->where('type', Transaction::WITHDRAWAL)->where('status', Transaction::PENDING)->first()) {
            Toastr::error('Failed: Awaiting Withdrawal Approval');
            return back();
        }


        $withdrawalData = $this->withdrawalData();

        if ($request->amount > $withdrawalData['difference']) {

            $userPlan = UserInvestmentPlan::where('user_id', auth()->id())->first();
            if (!$this->userInvestmentDateCondition() &&  $userPlan) {
                Toastr::error('Declined, you need to add atleast 1 new plan');
                return back();
            }


            $appName = config('app.name');

            Toastr::info("You have less liquid $appName shares, please try a lower amount or promo withdrawal");
            return back();
        }

        $rates = CountryHelper::getRates();
        $method = PaymentMethod::find($request->method);
        [$charge['percentage'], $charge['flatFee']] = explode('%', $method['rate']);

        $amount = $request->amount * $rates[$method['currency']];

        $totalCharge = ($charge['percentage'] * $amount) + $charge['flatFee'];

        $user = User::find(auth()->id());
        $this->deductFromBalance($request->amount, $request->wallet);
        $transaction =  $user->transactions()->create([
            'amount' => $amount - $totalCharge,
            'currency' =>    $method['currency'],
            'method' => $method['name'],
            'amount_before_deduction' => $amount,
            'status' => Transaction::PENDING,
            'address' => str_replace(' ', '', $request->address),
            'type' => Transaction::WITHDRAWAL,
            'wallet' => 'deposit',
            'code' => strtoupper(Str::random(8)),
        ]);



        $currency = config('app.currency');
        Toastr::success("Your withdrawal request of {$request->amount} {$currency} was sent successfully.");


        return back();
    }

    private function withdrawalData()
    {
        $user = User::find(auth()->id());
        $data['referralDeposit'] = $user->wallet->referral_deposit * 10;
        $data['referralCommission'] = $user->wallet->referral_commission * 10;

        $data['completedWihdrawal'] = Transaction::where('type', Transaction::WITHDRAWAL)->where('status', Transaction::COMPLETED)->sum('amount_before_deduction');
        $data['pendingWithdrawal'] = Transaction::where('type', Transaction::WITHDRAWAL)->where('status', Transaction::PENDING)->sum('amount_before_deduction');
        $data['completedWithdrawal'] = $data['pendingWithdrawal'] + $data['completedWihdrawal'];
        $data['difference'] =   $data['referralDeposit'] +  $data['referralCommission'] -    $data['completedWihdrawal'] -      $data['pendingWithdrawal'];
        return $data;
    }

    private function userInvestmentDateCondition()
    {

        $userPlan = UserInvestmentPlan::where('user_id', auth()->id())->where('created_at', '>', Carbon::today()->day(2)->month(4))->first();

        return  $userPlan ? true : false;
    }

    private function accountBalance($selectedWallet)
    {

        $wallet = User::find(auth()->id())->wallet;

        $balance = $wallet->profits;



        return $balance;
    }

    public static function approveWithdraw($id)
    {

        $transaction  = Transaction::find($id);

        $transaction->update([
            'status' => Transaction::COMPLETED,
        ]);
        $appName = config('app.name');

        $amount  =  number_format($transaction['amount']);
        $name = explode(' ', $transaction['user']['name'])[0];
        $message = "Dear {$name}, your withdrawal of {$amount}{$transaction['currency']} has been received to {$transaction['address']}.
$appName in TZ";

        dispatch(function () use ($message, $transaction) {
            CelcomHelper::sendMessage($transaction->user->phone_number, $message);
        });
    }

    public static function declineWithdrawal($id)
    {
        $transaction =  Transaction::find($id);

        $transaction->where('id', $id)->update([
            'status' => Transaction::FAILED,
        ]);

        $rates = CountryHelper::getRates();
        $amount = $transaction->amount_before_deduction / $rates[$transaction['currency']];

        Wallet::where('user_id', $transaction['user_id'])->increment('profits',  $amount);

        return true;
    }

    private function deductFromBalance($amount, $wallet)
    {
        $user = User::find(auth()->id());

        TradeSettingHelper::decrement(TradeSetting::EXTRA_WALLET, ($amount * 0.8) + 0.3);

        $deductionWallets = ['profits'];

        $remainingAmount = $amount;
        foreach ($deductionWallets as $item) {
            if ($remainingAmount > 0) {
                if ($remainingAmount < $user->wallet[$item]) {
                    $user->wallet()->decrement($item, $remainingAmount);
                    break;
                } else {
                    $user->wallet()->decrement($item, $user->wallet[$item]);
                    $remainingAmount -= $user->wallet[$item];
                    continue;
                }
            }
        }
    }
}
