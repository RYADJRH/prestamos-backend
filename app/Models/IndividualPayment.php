<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Enum\StatePaymentEnum;
use App\Traits\Observable;

class IndividualPayment extends Model
{
    use HasFactory, Observable;

    protected $table        = 'individual_payments';
    protected $primaryKey   = 'id_payment';

    protected $guarded  = [
        'id_payment',
        'state_payment',
    ];
    protected $fillable = [
        'num_payment',
        'date_payment',
        'amount_payment_period',
        'remaining_balance',
        'id_borrow'
    ];
    protected $casts    = [
        'date_payment'   => 'date:Y-m-d',
        'state_payment'  => StatePaymentEnum::class
    ];

    protected $appends = ['amount_payment_period_decimal', 'remaining_balance_decimal'];

    public function amountPaymentPeriod(): Attribute
    {
        return new Attribute(
            set: fn ($value) => round($value * 100, 2),
        );
    }

    public function amountPaymentPeriodDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['amount_payment_period'] > 0 ? round($attributes['amount_payment_period'] / 100, 2) : 0,
        );
    }

    public function remainingBalance(): Attribute
    {
        return new Attribute(
            set: fn ($value) => round($value * 100, 2),
        );
    }

    public function remainingBalanceDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['remaining_balance'] > 0 ? round($attributes['remaining_balance'] / 100, 2) : 0,
        );
    }

    public function individualLoan()
    {
        return $this->belongsTo(IndividualBorrow::class, 'id_borrow', 'id_borrow');
    }
}
