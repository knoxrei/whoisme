<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdCampaign extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}
