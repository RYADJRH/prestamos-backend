<?php

namespace App\Models;

use App\Enum\StatePaymentEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;


class IndividualBorrow extends Model
{
    use HasFactory;
    protected $table        = 'individual_borrows';
    protected $primaryKey   = 'id_borrow';

    protected $guarded = [
        'id_borrow',
        'state_borrow',
    ];
    protected $fillable = [
        'number_payments',
        'amount_borrow',
        'amount_interest',
        'id_borrower'
    ];

    protected $casts    = [
        'state_borrow' => StatePaymentEnum::class
    ];

    protected $appends = ['amount_pay', 'amount_pay_decimal', 'amount_borrow_decimal', 'amount_interest_decimal'];


    public function amountPay(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => round($attributes['amount_borrow'] + $attributes['amount_interest'], 2)
        );
    }

    public function amountPayDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => round(($attributes['amount_borrow'] + $attributes['amount_interest']) / 100, 2)
        );
    }

    public function amountBorrow(): Attribute
    {
        return new Attribute(
            set: fn ($value) => round($value * 100, 2),
        );
    }

    public function amountBorrowDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => round(($attributes['amount_borrow'] > 0 ? $attributes['amount_borrow'] / 100 : 0), 2)
        );
    }

    public function amountInterest(): Attribute
    {
        return new Attribute(
            set: fn ($value) => round($value * 100, 2),
        );
    }

    public function borrower()
    {
        return $this->belongsTo(Borrower::class, 'id_borrower', 'id_borrower');
    }

    public function amountInterestDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => round(($attributes['amount_interest'] > 0 ? $attributes['amount_interest'] / 100 : 0), 2)
        );
    }

    public function individualPayments()
    {
        return $this->hasMany(IndividualPayment::class, 'id_borrow', 'id_borrow')
        ->orderBy('num_payment','ASC');
    }

    public function paymentsUnPaidInProccess()
    {
        return $this->hasMany(IndividualPayment::class, 'id_borrow', 'id_borrow')->where('state_payment', '!=', StatePaymentEnum::STATUS_PAID);
    }
}
