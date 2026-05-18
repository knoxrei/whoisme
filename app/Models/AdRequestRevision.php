<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdRequestRevision extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function request()
    {
        return $this->belongsTo(AdRequest::class, 'ad_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
