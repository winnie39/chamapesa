<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\CelcomHelper;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\ReferralHelper;
use App\Http\Controllers\Helpers\ToastNotificationHelper;
use App\Mail\DepositMail;
use App\Mail\PendingDeposit;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DepositController extends Controller
{
    function index()
    {
        $data['methods'] = PaymentMethod::get();
        $data['countries'] = CountryHelper::getAllCountries();
        $data['rates'] = CountryHelper::getRates();

        return view('pages.deposit', compact('data'));
    }

    public function deposit(Request $request)
    {

        $allowedPaymentMethods = PaymentMethod::get()->pluck('id');

        $request->validate([
            'amount' => 'required|numeric|min:6.5',
            // 'transaction_id' => 'required|string|alpha_num|size:10',
            'lastname' => 'required_if:method-parameter,mpesa',
            'firstname' => 'required_if:method-parameter,mpesa',
        ], [
            'address.required' => 'This field is required',
            'method.required' => 'The payment method field is required',
            'method.in' => 'Invalid payment method',

        ]);

        if ($request->get('method-parameter') == 'crypto') {

            return redirect()->away($this->getNowPaymentInvoiceLink($request->amount));
        }


        $transaction =  Transaction::where('address', 'like', strtoupper($request->firstname) . ' ' . strtoupper($request->lastname))->where('status', Transaction::UNCOMPLETED)->first();

        if ($transaction) {
            User::find(auth()->id())->wallet()->increment('deposit', $transaction->amount);
            $transaction->update([
                'status' => Transaction::COMPLETED,
                'user_id' => auth()->id(),
                'wallet' => 'deposit',
            ]);
            $data = [
                'user' => User::find(auth()->id()),
                'transaction' => $transaction,
            ];

            Toastr::success('Deposit of  ' . (float) $transaction->amount . ' USD has been approved successfully.');
            $amount  =  (float) $transaction['amount'];

            $name = explode(' ', $transaction['user']['name'])[0];

            $message = "Dear {$name}, your deposit of {$amount}{$transaction['currency']} has been approved successfully.  
Invest more to earn more with Trueflip in TZ";

            dispatch(function () use ($message, $transaction) {
                CelcomHelper::sendMessage($transaction->user->phone_number, $message);
            });


            return back();
        } else {
            Toastr::error('Trasaction not found, please try again.');
        }


        return back();
    }

    private function getNowPaymentInvoiceLink($amount)
    {
        $orderId = Str::random(8);
        $code = Str::random(10);
        $response = Http::withHeaders([
            'x-api-key' => env('NOW_PAYMENT_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.nowpayments.io/v1/invoice', [
            'price_amount' => $amount + 2.6,
            'price_currency' => 'usd',
            'order_id' =>  $orderId,
            'order_description' => 'TRUEFLIPTZ',
            'success_url' => url('/api/deposit-success/' . $code),
            'cancel_url' =>  url('/api/deposit-failure/' . $code),
        ]);

        $responseData = json_decode($response->body());

        $transaction =   User::find(auth()->id())->transactions()->create([
            'amount' => $amount,
            'currency' => 'USD',
            'method' => 'CRYPTOCURRENCY',
            'amount_before_deduction' => $amount,
            'status' => Transaction::PENDING,
            'type' => Transaction::DEPOSIT,
            'wallet' => 'deposit',
            'code' => $responseData->id,
        ]);


        return ($responseData->invoice_url);
    }

    public static function approveManualDeposit($id)
    {

        if (!in_array(auth()->user()->phone_number, config('app.superadmins'))) {
            abort(403, 'Tulia mzee 😂😂😂');
        }

        $transaction  = Transaction::find($id);

        $conversion = CountryHelper::convertCurrency($transaction->amount, $transaction->currency, config('app.currency'));
        $amount = $transaction->amount * $conversion->constant;

        // (new ReferralHelper())->newRef($transaction['user_id'],   $amount);


        $transaction->update([
            'status' => Transaction::COMPLETED,
            'currency' => config('app.currency'),
            'amount' => $amount,
            'amount_before_deduction' => $amount,
        ]);


        if (File::exists(public_path('/storage' . $transaction->image))) {
            File::delete(public_path('/storage' . $transaction->image));
        }


        User::find($transaction->user_id)->wallet()->increment($transaction->wallet, $amount);


        return back();
    }

    public static function rejectManualPayments($id)
    {

        if (!in_array(auth()->user()->phone_number, config('app.superadmins'))) {
            abort(403, 'Tulia mzee 😂😂😂');
        }


        $transaction  = Transaction::find($id);

        $transaction->update([
            'status' => Transaction::FAILED,
        ]);

        if (File::exists(public_path($transaction->image))) {
            File::delete(public_path($transaction->image));
        }

        return back();
    }


    public static function payLevelOne($userId, $amount)
    {
        $rate = 0.1;
        $user = User::find($userId);
        $profit  = $amount * $rate;
        if ($user->referral->referrer_id) {

            $referer = User::find($user->referral->referrer_id);


            if ($referer) {
                $referer->wallet()->increment('referral_commission', $rate * $amount);
            } else {
                return;
            }

            Transaction::create([
                'user_id' => $referer->user_id,
                'amount' => $profit,
                'status' => Transaction::COMPLETED,
                'type' => Transaction::REFERRALS,
                'transaction_code' => Str::random(8),
                'wallet' => 'referral_commission',
                'address' => 'PELYCAN',
                'description' => 'Deposit',
            ]);
        }

        return;
    }
}
