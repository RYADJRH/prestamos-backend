<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait Observable
{
    public static function bootObservable()
    {
        static::created(function (Model $model) {
            Log::channel('history')->info("CREADO");
            Log::channel('history')->info($model);
            Log::channel('history')->info("-------------------------------------------------------------------------------------");
        });
        static::updated(function (Model $model) {
            Log::channel('history')->info("ACTUALIZADO");
            Log::channel('history')->info($model);
            Log::channel('history')->info("-------------------------------------------------------------------------------------");
        });
        static::deleted(function (Model $model) {
            Log::channel('history')->info("ELIMINADO");
            Log::channel('history')->info($model);
            Log::channel('history')->info("-------------------------------------------------------------------------------------");
        });
    }
}
