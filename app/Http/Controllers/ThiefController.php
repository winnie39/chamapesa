<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class ThiefController extends Controller
{
    public function index()
    {
        $data['mostTransactions'] = User::query()
            ->has('transactions')
            ->with(['transactions' => function ($query) {
                $query->where('status', Transaction::COMPLETED);
            }])
            ->has('kyc')
            ->get();

        // Now, iterate over the users and calculate the sum of transactions for each user
        foreach ($data['mostTransactions'] as $user) {
            $sumOfTransactions = $user->transactions->where('status', Transaction::COMPLETED)->sum('amount');
            $user->setAttribute('sumOfTransactions', $sumOfTransactions);
        }

        // Sort the records by sumOfTransactions in descending order
        $data['mostTransactions'] = $data['mostTransactions']->sortByDesc('sumOfTransactions')->values()->all();



        return view('pages.thiefs', compact('data'));
    }
}
