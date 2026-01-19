<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\ReferralHelper;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionOrder extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->phone_number, config('app.superadmins'))) {
            abort(404);
        }


        return view('pages.create-transaction-order');
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:8|alpha_num',
            'amount' => 'required|numeric',
        ]);


        if (!in_array(auth()->user()->phone_number, config('app.superadmins'))) {
            abort(404);
        }

        $transaction = Transaction::where('code', str_replace(' ', '', strtoupper($request->code)))->where('status', Transaction::PENDING)->where('type', Transaction::DEPOSIT)->first();
        if (!$transaction) {

            $usedTransaction  = Transaction::where('code', str_replace(' ', '', strtoupper($request->code)))->first();
            if ($usedTransaction) {
                Toastr::error('Transaction ID is already used.');
                return back();
            } else {

                Transaction::query()->create([
                    'amount' => $request->amount,
                    'status' => Transaction::UNCOMPLETED,
                    'type' => Transaction::DEPOSIT,
                    'address' =>  config('app.name'),
                    'code' =>  str_replace(' ', '', strtoupper($request->code)),
                    'currency' => config('app.currency'),
                    'description' => 'DEPOSIT',
                    'method' =>  'MPESA',
                    'amount_before_deduction' => $request->amount,
                ]);

                Toastr::info('Order created successfully.');
            }
        } else {

            $transaction->update([
                'status' => Transaction::COMPLETED,
                'amount' => $request->amount,
            ]);

            // (new ReferralHelper())->newRef($transaction['user_id'], $request->amount);


            Wallet::where('user_id', $transaction['user_id'])->increment('deposit', $request->amount);
            Toastr::success("Transaction approved successfully.");
        }



        return back();
    }
}
