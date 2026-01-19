<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use App\Models\TradeSetting;
use Illuminate\Http\Request;

class TradeSettingHelper extends Controller
{
    public  static function getSetting($type)
    {
        if ($type == 'a') {
            return Trade::query()->whereRelation('user', function ($query) {
                return $query->whereNotIn('email', config('app.admins'));
            })->where('status', true)->sum('stake');
        }

        return   TradeSetting::where('type', $type)->first()->value;
    }

    public static function increment($type, $incrementBy)
    {
        TradeSetting::where('type', $type)->increment('value', $incrementBy);
    }


    public static function decrement($type, $decrementBy)
    {
        TradeSetting::where('type', $type)->decrement('value', $decrementBy);
    }

    public  static function update($type, $value)
    {
        TradeSetting::where('type', $type)->update(['value' => $value]);
    }
}
