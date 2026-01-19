<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(
            'https://isms.celcomafrica.com/api/services/sendsms',
            [
                "apikey" => "e552bdd7df563d7231aaec61031dbd43",
                "partnerID" => "36",
                "message" => "this is a test message",
                "shortcode" => "TEXTME",
                "mobile" => "0100801470"
            ]
        );

        return $response->body();
    }
}
