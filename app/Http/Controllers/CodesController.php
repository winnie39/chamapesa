<?php

namespace App\Http\Controllers;

use App\Events\BroadcastPromoCodes;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Models\Code;
use App\Models\CodePlan;
use App\Models\CodePlanUser;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Http\Request;
use Illuminate\Support\str;

class CodesController extends Controller
{
    public function plans()
    {
        $data['rates'] = CountryHelper::getRates();
        $data['plans'] = CodePlan::get();
        $data['activePlans'] = CodePlanUser::where('user_id', auth()->id())->get()->pluck('code_plan_id')->toArray();
        return view('pages.code-grab-plans', compact('data'));
    }

    public function codes()
    {

        $codes = Code::where('used', false)->where('expiry', '>', now())->get(['code', 'code_plan_id'])->toArray();

        $data['userCodePlans'] = CodePlanUser::where('user_id', auth()->id())->where('expiry_date', '>', now())->get()->pluck('code_plan_id')->toArray();

        $data['codes'] = array_filter($codes, fn ($item) => in_array($item['code_plan_id'], $data['userCodePlans']));

        return view('pages.grab-codes', compact('data'));
    }

    public function submitCode(Request $request)
    {

        if (!$request->code) {
            Toastr::info('The code field is required.');
            return back();
        }

        $promoCode = Code::query()->where('code', $request->code)->first();


        if (!$promoCode) {
            Toastr::info('The code provided does not exist');
            return back();
        }


        if ($promoCode['used']) {
            Toastr::info('The code provided is already used.');
            return back();
        }

        if (Carbon::parse($promoCode['expiry'])->lessThan(now())) {
            Toastr::info('The code provided is expired.');
            return back();
        }

        $data['userCodePlans'] = CodePlanUser::where('user_id', auth()->id())->where('expiry_date', '>', now())->get()->pluck('code_plan_id')->toArray();

        if (!in_array($promoCode['code_plan_id'], $data['userCodePlans'])) {
            Toastr::info('Failed, you are not subscribed to this plan.');
            return back();
        }

        $todaysCodes = Code::where('user_id', auth()->id())->where('code_plan_id', $promoCode['code_plan_id'])->where('updated_at', '>', Carbon::today())->count();

        if ($todaysCodes == 2) {
            Toastr::error('This code has been used.');
            return back();
        }

        $weeksCodes = Code::where('user_id', auth()->id())->where('code_plan_id', $promoCode['code_plan_id'])->where('updated_at', '>', Carbon::today()->subDays(5))->count();


        if ($weeksCodes == 4) {
            Toastr::error('This code has been used.');
            return back();
        }


        $promoCode->update([
            'user_id' => auth()->id(),
            'used' => true,
        ]);


        Wallet::where('user_id', auth()->id())->increment('referral_commission', $promoCode['plan']['winning_amount_per_code'] / 10);
        Transaction::create([
            'user_id' => auth()->id(),
            'amount' => $promoCode['plan']['winning_amount_per_code'],
            'status' => Transaction::COMPLETED,
            'type' => Transaction::WON_PROMO_CODE,
            'code' => $promoCode['code'],
            'method' => config('app.name'),
            'wallet' => 'referral_commission',
            'description' => 'Won code',
            'address' => config('app.name'),
            'image' => '/images/',
        ]);

        BroadcastPromoCodes::broadcast();

        Toastr::success('You have won ' . (float) ($promoCode['plan']['winning_amount_per_code']) . config('app.currency'));

        return back();
    }

    public function codesGenerator()
    {
        if (!in_array(auth()->user()->phone_number, config('app.superadmins'))) {
            abort(404);
        }

        $data['plans'] = CodePlan::get();


        return view('pages.codes-generator', compact('data'));
    }

    public function subscribe(Request $request)
    {
        if (!$request->plan) {
            Toastr::error('The plan field is required');
            return back();
        }

        $accountBalance = $this->accountBalance();

        $plan = CodePlan::findOrFail($request->plan);

        if ($plan['price'] > $accountBalance) {
            Toastr::error('Your balance is below ' . number_format($plan['price']));
            return back();
        }


        $data['activePlans'] = CodePlanUser::where('user_id', auth()->id())->get()->pluck('code_plan_id')->toArray();
        if (in_array($plan['id'], $data['activePlans'])) {
            Toastr::error('Sorry, you cannot have two subscriptions under the same plan. please try onother plan');
            return back();
        }

        CodePlanUser::create([
            'user_id' => auth()->id(),
            'code_plan_id' => $plan['id'],
            'expiry_date' => now()->addHours($plan['period_in_hours']),
        ]);

        Transaction::create([
            'user_id' => auth()->id(),
            'amount' => $plan['price'],
            'status' => Transaction::COMPLETED,
            'type' => Transaction::WON_PROMO_CODE,
            'code' => strtoupper(Str::random(8)),
            'method' => config('app.name'),
            'wallet' => 'investment_profits',
            'description' => 'Subscribe codes plan',
            'address' => config('app.name'),
            'image' => '/images/',
        ]);


        $this->deductFromBalance($plan['price']);

        Toastr::success('You have successfully subscribed to ' . $plan['name'] . '.');
        return redirect('/codes');
    }

    private function accountBalance()
    {
        $wallet = User::find(auth()->id())->wallet;
        $accountBalance = $wallet->deposit;
        return $accountBalance;
    }

    private function deductFromBalance($amount)
    {
        $user = User::find(auth()->id());

        $deductionWallets = ['deposit'];

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

    public function generateCodes(Request $request)
    {

        if (!in_array(auth()->user()->phone_number, config('app.superadmins'))) {
            abort(404);
        }

        $request->validate([
            'number_of_codes' => 'required|numeric',
            'plan' => 'required|numeric',
            'period' => 'required|numeric',
        ]);

        for ($i = 0; $i < $request->number_of_codes; $i++) {
            Code::create([
                'code_plan_id' => $request->plan,
                'code' => strtoupper(Str::random(10)),
                'expiry' => now()->addMinutes($request->period),
                'used' => false,
            ]);
        }

        BroadcastPromoCodes::broadcast();

        Toastr::success('Success');

        return back();
    }
}
