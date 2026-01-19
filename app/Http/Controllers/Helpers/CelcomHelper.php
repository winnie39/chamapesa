<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CelcomHelper extends Controller
{
    public static function sendMessage($number, $message)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(
            'https://isms.celcomafrica.com/api/services/sendsms',
            [
                "apikey" => config('app.celcom-api-key'),
                "partnerID" => config('app.celcom-patner-id'),
                "message" => $message,
                "shortcode" => config('app.celcom-short-code'),
                "mobile" => $number,
            ]
        );

        return $response;
        return true;
    }
}
