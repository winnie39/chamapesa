<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvestmentPlan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'next_payment',
        'expiry_date',
        'remaining_days',
        'total_received'
    ];

    public function getTotalReceivedAttribute()
    {
        $totalHours = Carbon::parse($this->created_at)->diffInHours($this->updated_at);
        return $totalHours * $this->plan->rate * $this->plan->price;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(InvestmentPlan::class, 'investment_plan_id', 'id');
    }

    public function getNextPaymentAttribute()
    {

        $updatedAtDate = Carbon::parse($this->updated_at);

        return $updatedAtDate->addHour();
    }

    public function getExpiryDateAttribute()
    {
        return Carbon::parse($this->created_at)->addHours($this->plan->period_in_hours);
    }

    public function getRemainingDaysAttribute()
    {
        return Carbon::parse($this->created_at)->addHours($this->plan->period_in_hours)->diffInDays(now());
    }
}
