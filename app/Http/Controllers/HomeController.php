<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\CountryHelper;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data['plans'] = InvestmentPlan::get();
        $data['rates'] = CountryHelper::getRates();
        return view('pages.home', compact('data'));
    }

    public function about()
    {
        return view('pages.about');
    }
}
