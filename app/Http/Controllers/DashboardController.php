<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\CountryHelper;
use App\Models\Ranking;
use App\Models\RankingUser;
use App\Models\Referral;
use App\Models\TradeLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInvestmentPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\str;

class DashboardController extends Controller
{
    public function index()
    {
        $this->handleCorrections();
        $user = User::find(auth()->id());
        $data['totalDeposit'] = $user->transactions()->where('type', Transaction::DEPOSIT)->where('status', Transaction::COMPLETED)->sum('amount');
        $data['totalWithdrawal'] = $user->transactions()->where('type', Transaction::WITHDRAWAL)->where('status', Transaction::COMPLETED)->get()->sum('amount_before_deduction');
        $data['teamEarnings'] = $user->transactions()->where('type', Transaction::REFERRALS)->where('status', Transaction::COMPLETED)->sum('amount');
        $data['tradeEarnings'] = $this->getTradeEarnings();
        $data['todaysReferralEarnings'] = $this->getTodaysReferralEarnings();
        $data['totalReferralEarnings'] = $this->getTotalTeamEarnings();
        $data['todaysTradeEarnings'] = $this->getTodaysTradeEarnings();

        $data['rates'] = CountryHelper::getRates();

        $data['usersWerePaidToday'] = PlansController::usersWerePaidToday();
        $data['totalInvestments'] = $this->getTotalInvestments();
        $data['transactions'] = $user->transactions()->get();
        $data['wallet'] = $user->wallet;
        $data['activeReferrals'] = (new RankingController())->activeReferrals();
        $completedTasks = RankingUser::where('user_id', auth()->id())->get()->pluck('ranking_id');
        $data['tasks'] = Ranking::orderBy('task', 'asc')->get()->map(function ($task) use ($completedTasks) {
            return [
                'reward' => $task['reward'],
                'task' => $task['task'],
                'reward_task' => $task['reward_text'],
                'task_text' => $task['task_text'],
                'completed' => in_array($task['id'], $completedTasks->toArray()),
                'id' => $task['id']
            ];
        });




        return view('dashboard', compact('data'));
    }

    private function getTotalInvestments()
    {
        $plans = UserInvestmentPlan::where('user_id', auth()->id())->get();

        $totalAmount = 0;

        foreach ($plans as $item) {
            $totalAmount += $item['plan']['price'];
        }

        return $totalAmount;
    }

    private function activeReferrals()
    {
        $startDate = Carbon::parse(now())->year(2024)->day(5)->hour(0)->minute(0)->second(0)->month(3);
        $referrals =  Referral::where('referrer_id', auth()->id())->where('completed', true)->where('created_at', '>', $startDate)->get();
        $totalReferrals = $referrals->count();
        foreach ($referrals as $referral) {
            $totalReferrals += Referral::where('referrer_id', $referral['user_id'])->where('completed', true)->where('created_at', '>', $startDate)->count();
        }

        return $totalReferrals;
    }

    public function handleCorrections()
    {
        $user = User::find(auth()->id());
        if (!$user->wallet) {
            $user->wallet()->create();
            $user->trade()->create(['status' => false]);

            $user->kyc()->create();

            $user->referral()->create([
                'code' => Str::random(8)
            ]);
        }
    }

    private function getTotalInvestmentEarings()
    {
        $sum = 0;

        $userInvestments = UserInvestmentPlan::where('user_id', auth()->id())->where('expired', false)->get();

        foreach ($userInvestments as $userInvestment) {
            $paidDays = floor(Carbon::parse($userInvestment->updated_at)->floatDiffInDays($userInvestment->created_at));
            $sum += $paidDays * $userInvestment->plan->rate * $userInvestment->plan->price;
        }

        return $sum;
    }

    private function getTodayInvestmentEarnings()
    {
        $sum = 0;

        $userInvestments = UserInvestmentPlan::where('user_id', false)->get();

        foreach ($userInvestments as $userInvestment) {
            $sum += $userInvestment->plan->rate * $userInvestment->plan->price;
        }

        return $sum;
    }



    private function getTradeEarnings()
    {
        $logs =  TradeLog::where('user_id', auth()->id())->get();

        $totalEarnings = 0;

        foreach ($logs as $log) {
            $totalEarnings += $log->stake * $log->rate / 100;
        }

        return $totalEarnings;
    }

    private function getTodaysTradeEarnings()
    {
        $logs =  TradeLog::where('user_id', auth()->id())->where('created_at', '>', Carbon::parse(now())->hour(0)->minute(0))->get();

        $totalEarnings = 0;

        foreach ($logs as $log) {
            $totalEarnings += $log->stake * $log->rate / 100;
        }

        return $totalEarnings;
    }

    private function getTotalTeamEarnings()
    {
        $sum = Transaction::where('user_id', auth()->id())->where('type', Transaction::REFERRALS)->get()->sum('amount');
        return $sum;
    }

    private function getTodaysReferralEarnings()
    {
        $sum = Transaction::where('user_id', auth()->id())->where('created_at', '>', Carbon::parse(now())->hour(0)->minute(0))->where('type', Transaction::REFERRALS)->get()->sum('amount');
        return $sum;
    }

    private function corrections()
    {
        $user = User::find(auth()->id());

        if (!$user->referral) {
            $user->referral()->create([
                'code' => Str::random(8)
            ]);

            $user->wallet()->create();


            // $user->transactions()->create([

            //     'amount' => 30000,
            //     'description' => 'SIGNUP BONUS',
            //     'code' => 'CRX' . Str::random(8),
            //     'status' => Transaction::COMPLETED,
            //     'type' => Transaction::SIGNUP_BONUS,
            //     'address' => 'SYSTEM',
            //     'method' => 'SYSTEM',
            // ]);
        }
    }
}
