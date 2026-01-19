<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $depositsData = $this->getPaymentsData();
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Total Users Today', User::where('created_at', '>', Carbon::parse(now())->minute(00)->hour(00)->second(00))->count()),
            Stat::make('Active Users', User::get()->where('has_plan', '!=', null)->count()),
            Stat::make('Completed Deposit', Transaction::where('type', Transaction::DEPOSIT)->where('status', Transaction::COMPLETED)->sum('amount_before_deduction')),
            Stat::make('Pending Deposit', Transaction::where('type', Transaction::DEPOSIT)->where('status', Transaction::PENDING)->sum('amount_before_deduction')),

            Stat::make('Uncompleted deposits', Transaction::where('type', Transaction::DEPOSIT)->where('status', Transaction::UNCOMPLETED)->sum('amount_before_deduction')),
            Stat::make('Uncompleted deposits count', Transaction::where('type', Transaction::DEPOSIT)->where('status', Transaction::UNCOMPLETED)->count()),

            Stat::make('Completed Withdrawals', Transaction::where('type', Transaction::WITHDRAWAL)->where('status', Transaction::COMPLETED)->sum('amount')),
            Stat::make('Completed Withdrawals before deduction', Transaction::where('type', Transaction::WITHDRAWAL)->where('status', Transaction::COMPLETED)->sum('amount_before_deduction')),


            Stat::make('Referral transactions', Transaction::where('type', Transaction::REFERRALS)->sum('amount')),
            Stat::make('Pending Withdrawals', Transaction::where('type', Transaction::WITHDRAWAL)->where('status', Transaction::PENDING)->sum('amount_before_deduction')),
            Stat::make('Pending Withdrawals Count', Transaction::where('type', Transaction::WITHDRAWAL)->where('status', Transaction::PENDING)->count()),
            Stat::make('Completed deposit Count', Transaction::where('type', Transaction::DEPOSIT)->where('status', Transaction::COMPLETED)->count()),
            Stat::make('Completed Withdrawals Count', Transaction::where('type', Transaction::WITHDRAWAL)->where('status', Transaction::COMPLETED)->count()),
            ...$depositsData,
        ];
    }

    protected function getPaymentsData()
    {

        $endDate = Carbon::now();
        $startDate = Carbon::now()->day(27)->month(2);

        $transactions = Transaction::where('type', Transaction::DEPOSIT)
            ->where('status', Transaction::COMPLETED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($transaction) {
                return $transaction->created_at->format('Y-m-d');
            })
            ->map(function ($transactionsForDay, $date) {
                $totalAmount = $transactionsForDay->sum('amount_before_deduction');
                return [
                    'date' => strtolower(Carbon::parse($date)->format('jS F')),
                    'amount' => $totalAmount,
                ];
            })
            ->values();
        $data = [];

        foreach ($transactions as $transaction) {
            $stat = Stat::make('Deposits on ' .  $transaction['date'], $transaction['amount']);
            array_push($data,  $stat);
        }

        return $data;
    }
}
