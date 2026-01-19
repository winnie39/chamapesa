<?php

namespace App\Console\Commands;

use App\Http\Controllers\Helpers\InvestmentsWorker;
use Illuminate\Console\Command;

class InvestmentWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:investment-worker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        InvestmentsWorker::work();
    }
}
