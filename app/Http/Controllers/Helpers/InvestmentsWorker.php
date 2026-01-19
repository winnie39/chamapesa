<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Models\UserInvestmentPlan;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvestmentsWorker extends Controller
{
    public static function work()
    {

        self::removeExpired();
        $userInvestmentPlans = UserInvestmentPlan::has('user')->where('expired', false)->get();

        foreach ($userInvestmentPlans as $userInvestmentPlan) {
            $planTime = Carbon::parse($userInvestmentPlan->updated_at);

            $differenceInHours = Carbon::parse($planTime)->diffInSeconds(now());

            if ($differenceInHours > 3600) {
                $unpaidDays = 1;
                $amountToPay = $userInvestmentPlan->plan->price * $userInvestmentPlan->plan->rate * $unpaidDays;

                Wallet::where('user_id', $userInvestmentPlan->user_id)->increment('profits', $amountToPay);

                $userInvestmentPlan->update([
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private static function removeExpired()
    {
        $monthlyBoosters = UserInvestmentPlan::where('expired', false)->get();

        foreach ($monthlyBoosters as $booster) {
            $boosterAge = Carbon::parse($booster['created_at'])->floatDiffInHours(now());
            if ($boosterAge > $booster->plan->period_in_hours) {
                $booster->update([
                    'expired' => true,
                ]);
            }
        }
    }
}
