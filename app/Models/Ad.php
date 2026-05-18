<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function campaign()
    {
        return $this->belongsTo(AdCampaign::class, 'ad_campaign_id');
    }

    public function requests()
    {
        return $this->hasMany(AdRequest::class);
    }

    public function statistics()
    {
        return $this->hasMany(AdStatistic::class);
    }
}
