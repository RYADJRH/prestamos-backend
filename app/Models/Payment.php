<?php

namespace App\Models;

use App\Enum\StatePaymentEnum;
use App\Traits\Observable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, Observable;

    protected $table        = 'payments';
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
        'id_group_borrower'
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

    public function borrower()
    {
        return $this->hasOneThrough(Borrower::class, GroupBorrower::class, 'id_group_borrower', 'id_borrower', 'id_group_borrower', 'id_borrower');
    }

    public function groupBorrower()
    {
        return $this->hasOne(GroupBorrower::class, 'id_group_borrower', 'id_group_borrower');
    }
}
