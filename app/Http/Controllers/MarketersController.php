<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\ReferralLevel;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInvestmentPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MarketersController extends Controller
{
    private $referrers = [];
    private $referrerId;
    private $marketersData = [];

    public function index(Request $request)
    {

        if (!in_array(auth()->user()->phone_number, config('app.admins'))) {
            abort(404);
        }

        $data['marketers'] = $this->getMarketersData();
        $data['levels'] = $this->getAllowedReferralLevels();
        return view('pages.marketers', compact('data'));
    }


    public function getMarketersData()
    {

        $levels =  ReferralLevel::orderBy('level', 'asc')->get()->keyBy('level');

        // return $levels[1];

        $marketers = User::whereIn('phone_number', config('app.admins'))->get();
        // $marketers = User::whereIn('email', config('app.admins'))->get();

        $marketersIds = $marketers->pluck('id')->toArray();

        $users = User::whereRelation('referral', function ($query) {
            $query->where('referrer_id', '>', 0);
        })->get();

        foreach ($users as $user) {
            $this->getReferrals($user);
        }

        foreach ($marketers as $marketer) {
            $this->marketersData[$marketer['id']]['user'] = $marketer;
            $this->marketersData[$marketer['id']]['referrals'] = [];
        }

        // return $this->referrers;
        foreach ($this->referrers as $referral) {
            foreach ($referral['referrals'] as $ref) {

                if (in_array($ref['id'], $marketersIds)) {

                    $refereredUserData = [
                        'user' => $referral['user'],
                        'transactions' => $referral['transactions'],
                        'amount' => ((float) $referral['transactions']) * $levels[$ref['level']]['rate'],
                        'level' => $ref['level']
                    ];

                    array_push($this->marketersData[$ref['id']]['referrals'],  $refereredUserData);
                }
            }
        }

        $marketersDataManipulation = [];

        foreach ($this->marketersData as $data) {
            $refs =  collect($data['referrals'])->groupBy('level');
            array_push($marketersDataManipulation, ['refs' => $refs, 'user' => $data['user']]);
        }


        $returnData = [];

        foreach ($marketersDataManipulation as $data) {

            $refsss = $data['refs']->map(function ($value) {
                return $value->sum('transactions');
            });



            $myUserData = [
                'user' => $data['user'],
                'refs' => $data['refs'],
                'sum' => $refsss,
            ];

            array_push($returnData, $myUserData);
        }

        return $returnData ?? [];


        return $marketersDataManipulation;
        return $this->marketersData;


        $levels =  ReferralLevel::get();
        return view('pages.marketers');
    }

    private function getReferrals($user)
    {

        $date = Carbon::parse(now())->day(12)->hour(19);
        $transactions = UserInvestmentPlan::where('user_id', $user['id'])->withSum('plan', 'price')->get()->sum('plan_sum_price');
        // $transactions = 0;
        // foreach ($userInvestments as $item) {
        //     $transactions +=   $item->plan_sum_price;
        // }
        // $transactions  = $user->transactions()->where('type', Transaction::DEPOSIT)->where('status', Transaction::COMPLETED)->sum('amount');
        $refLevels = $this->getAllowedReferralLevels();
        if ($transactions > 0) {
            $this->referrers[$user['id']] = [];
            $this->referrers[$user['id']]['referrals'] = [];
            $this->referrers[$user['id']]['user'] = $user;
            $this->referrers[$user['id']]['transactions'] = $transactions;
            foreach ($refLevels as $refLevel) {
                $level = $refLevel->level;

                if ($level === 1) {
                    $referrerId = Referral::where('user_id', $user['id'])->first()->referrer_id;
                    if ($referrerId !== null) {
                        $this->referrerId = $referrerId;
                        array_push($this->referrers[$user['id']]['referrals'], ["level" => $level, "id" => $referrerId]);
                    } else {
                        return true;
                    }
                } else {
                    $referrerId = Referral::where('user_id', $this->referrerId)->first()->referrer_id;
                    if ($referrerId !== null) {
                        $this->referrerId = $referrerId;
                        array_push($this->referrers[$user['id']]['referrals'], ["level" => $level, "id" => $referrerId]);
                    } else {
                        return true;
                    }
                }
            }
        }
    }



    private function getAllowedReferralLevels()
    {
        return ReferralLevel::orderBy('level', 'asc')->get();
    }

    public function topPerformers()
    {

        if (!in_array(auth()->user()->phone_number, config('app.admins'))) {
            abort(404);
        }

        $data['users'] = User::withCount('downlines')->get()->where('downlines_count', '>', 1)->sortByDesc('downlines_count');
        return view('top-performers', compact('data'));
    }
}
