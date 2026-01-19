<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\RankingUser;
use App\Models\Referral;
use App\Models\Transaction;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RankingController extends Controller
{
    public function index()
    {
        $data['activeReferrals'] = $this->activeReferrals();
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


        return view('pages.ranking', compact('data'));
    }

    public function activeReferrals()
    {
        $startDate = Carbon::parse(now())->year(2024)->day(5)->hour(0)->minute(0)->second(0)->month(3);
        $referrals =  Referral::where('referrer_id', auth()->id())->get();
        $totalReferrals = $referrals->where('completed', true)->count();
        foreach ($referrals as $referral) {
            $totalReferrals += Referral::where('referrer_id', $referral['user_id'])->get()->where('completed', true)->count();
        }

        return $totalReferrals;
    }

    public function claim(Request $request)
    {

        $task = $request->task;

        if (!$task) {
            Toastr::error('No task was provided');
            return back();
        }

        $ranking = Ranking::findOrFail($task);

        $userReferrals = $this->activeReferrals();

        if ((int) $userReferrals < $ranking['task']) {
            Toastr::error("Your active referral  is below " . (int) $ranking['task'] . '.');
            return back();
        }

        $taskIsCompleted = RankingUser::where('user_id', auth()->id())->where('ranking_id', $ranking['id'])->first();

        if ($taskIsCompleted) {
            Toastr::info('You have already completed this task');
            return back();
        }

        $user = User::find(auth()->id());


        RankingUser::query()->create([
            'user_id' => auth()->id(),
            'ranking_id' => $ranking['id']
        ]);


        $user->transactions()->create([
            'user_id' => auth()->id(),
            'amount' => $ranking['reward'],
            'status' => Transaction::COMPLETED,
            'type' => Transaction::TASK_COMPLETED,
            'code' => strtoupper(Str::random(8)),
            'method' => config('app.name'),
            'wallet' => 'investment_profits',
            'description' => 'Task completed',
            'address' => config('app.name'),
            'image' => '/images/',
        ]);

        $user->wallet()->increment('profits', $ranking['reward']);


        Toastr::success('Congratulations, task completed successfully. You have won ' . $ranking['reward'] . 'KES');

        return back();
    }
}
