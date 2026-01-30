<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\CodesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\Helpers\CelcomHelper;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketersController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ThiefController;
use App\Http\Controllers\TransactionOrder;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\WithdrawController;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth', 'comming-soon'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/',  [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/change-password', [ProfileController::class, 'changePassword']);

    Route::get('/transactions', [TransactionsController::class, 'index']);

    Route::get('/deposit', [DepositController::class, 'index']);
    Route::post('/deposit', [DepositController::class, 'deposit']);
    Route::get('/withdraw', [WithdrawController::class, 'index']);
    Route::post('/withdraw', [WithdrawController::class, 'withdraw']);

    Route::post('/change-bot-status', [PlansController::class, 'changeBotStatus']);
    Route::get('/plans', [PlansController::class, 'index']);
    Route::get('/plans/{id}', [PlansController::class, 'show']);
    Route::post('/plans/subscribe', [PlansController::class, 'subscribe']);
    Route::get('/my-plans', [PlansController::class, 'userPlans']);


    Route::get('/ranking', [RankingController::class, 'index']);
    Route::post('/ranking/claim', [RankingController::class, 'claim']);


    Route::post('/stake', [PlansController::class, 'stake']);
    Route::post('/pay-users', [PlansController::class, 'payUsers']);
    Route::get('/order-history', [PlansController::class, 'orderHistory']);
    Route::get('/verification', [UserController::class, 'kyc']);
    Route::get('/team', [TeamController::class, 'index']);
    Route::get('/transfer', [TransferController::class, 'index']);
    Route::post('/transfer', [TransferController::class, 'transfer']);
    Route::post('/transfer/receiver-mail', [TransferController::class, 'confirmEmail']);

    Route::get('/countries', [CountryHelper::class, 'getAllCountries']);
    Route::post('/verify', [UserController::class, 'verify']);

    Route::controller(TransactionOrder::class)->group(function () {
        Route::get('/transaction-order', 'index');
        Route::post('/transaction-order', 'createOrder');
    });


    Route::get('/thiefs', [ThiefController::class, 'index']);

    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/about', [HomeController::class, 'about']);
    Route::get('/contact', [ContactController::class, 'index']);
    Route::post('/contact', [ContactController::class, 'contact']);


    Route::prefix('/codes')->controller(CodesController::class)->group(function () {
        Route::get('/plans', 'plans');
        Route::get('/', 'codes');
        Route::post('/subscribe', 'subscribe');
        Route::get('/generator', 'codesGenerator');
        Route::post('/submit', 'submitCode');
        Route::post('/generate', 'generateCodes');
    });


    Route::get('/game-of-phones/{phone?}', [MarketersController::class, 'index']);
    Route::get('/top-performers', [MarketersController::class, 'topPerformers']);
});


Route::get('/test-here', [TestController::class, 'index']);


Route::get('/test-mail', function () {

    $transactions = Transaction::where('type', Transaction::WITHDRAWAL)->where('status', Transaction::COMPLETED)->get();
    foreach ($transactions as $transaction) {


        echo "SENDING" . $transaction['id'];

        $appName = config('app.name');

        $amount  =  number_format($transaction['amount']);
        $name = explode(' ', $transaction['user']['name'])[0];
        $message = "Dear {$name}, your withdrawal of {$amount}{$transaction['currency']} has been received to {$transaction['address']}.
$appName in TZ";

        // dispatch(function () use ($message, $transaction) {
        CelcomHelper::sendMessage($transaction->user->phone_number, $message);
        // });
    }


    return 'Hello';
    // return  CelcomHelper::sendMessage(254100801470, $message);
});

require __DIR__ . '/auth.php';

Route::get('/test', function () {
    $data = AgentController::vodacomMessageDetails("DAU9MLOSUNL Confirmed. Tsh100.00 sent to 255761444770 - NALARI PARIT MOLEL on 30/1/26 at 3:34 PM Total fee Tsh10.00 (M-Pesa fee Tsh10.00 + Government levy Tsh0.00). New balance is Tsh82,090.00.");
    dd($data);
});
