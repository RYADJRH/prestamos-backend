<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AdjustPayment extends Model
{
    use HasFactory;


    protected $table        = 'adjust_payment';
    protected $primaryKey   = 'id';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'before_amount',
        'after_amount',
        'date_adjust_payment',
        'id_payment'
    ];

    protected $cast = [
        'date_adjust_payment' => 'date:Y-m-d',
    ];

    protected $appends = ['before_amount_decimal', 'after_amount_decimal'];

    public function beforeAmount(): Attribute
    {
        return new Attribute(
            set: fn ($value) => round($value * 100, 2),
        );
    }

    public function afterAmount(): Attribute
    {
        return new Attribute(
            set: fn ($value) => round($value * 100, 2),
        );
    }

    public function beforeAmountDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['before_amount'] > 0 ? round($attributes['before_amount'] / 100, 2) : 0,
        );
    }

    public function afterAmountDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['after_amount'] > 0 ? round($attributes['after_amount'] / 100, 2) : 0,
        );
    }
}
