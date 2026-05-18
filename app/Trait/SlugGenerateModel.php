<?php

namespace App\Trait;

use Illuminate\Support\Str;

trait SlugGenerateModel
{
    public static function bootSlugGenerateModel()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }


}
