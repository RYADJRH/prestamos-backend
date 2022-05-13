<?php

namespace App\Models;

use App\Enum\StatePaymentEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupBorrower extends Pivot
{
    use HasFactory;

    protected $table        = 'group_borrowers';
    protected $primaryKey   = 'id_group_borrower';


    protected $fillable = [
        'id_borrower',
        'id_group',
        'id_group_borrower',
        'amount_borrow',
        'amount_interest',
        'number_payments',
        'state_borrow',
    ];

    protected $appends = ['amount_pay', 'amount_pay_decimal', 'amount_borrow_decimal', 'amount_interest_decimal'];

    protected $casts    = [
        'state_borrow' => StatePaymentEnum::class
    ];

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

    public function amountInterestDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => round(($attributes['amount_interest'] > 0 ? $attributes['amount_interest'] / 100 : 0), 2)
        );
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_group_borrower', 'id_group_borrower')
            ->orderBy('num_payment', 'ASC');
    }

    public function paymentsPaid()
    {
        return $this->hasMany(Payment::class, 'id_group_borrower', 'id_group_borrower')
            ->where('state_payment', '=', StatePaymentEnum::STATUS_PAID);
    }

    public function paymentsUnPaid()
    {
        return $this->hasMany(Payment::class, 'id_group_borrower', 'id_group_borrower')
            ->where('state_payment', '=', StatePaymentEnum::STATUS_UNPAID);
    }

    public function paymentsInProccess()
    {
        return $this->hasMany(Payment::class, 'id_group_borrower', 'id_group_borrower')
            ->where('state_payment', '=', StatePaymentEnum::STATUS_INPROCCESS);
    }

    public function paymentsUnPaidInProccess()
    {
        return $this->hasMany(Payment::class, 'id_group_borrower', 'id_group_borrower')
            ->where('state_payment', '!=', StatePaymentEnum::STATUS_PAID);
    }
}
