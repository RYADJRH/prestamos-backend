<?php

namespace App\Models;

use App\Enum\StatePaymentEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupBorrower extends Model
{
    use HasFactory;

    protected $table        = 'group_borrowers';
    protected $primaryKey   = 'id_group_borrower';

    protected $guarded  = [
        'id_group_borrower',
        'amount_pay',
        'amount_borrow',
        'amount_interest',
        'state_borrow',
    ];
    protected $fillable = [
        'id_borrower',
        'id_group'
    ];

    protected $casts    = [
        'state_borrow' => StatePaymentEnum::class
    ];

    public function amountPay(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value * 100,
        );
    }

    public function amountPayDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['amount_pay'] > 0 ? $attributes['amount_pay'] / 100 : 0
        );
    }

    public function amountBorrow(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value * 100,
        );
    }

    public function amountBorrowDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['amount_borrow'] > 0 ? $attributes['amount_borrow'] / 100 : 0
        );
    }

    public function amountInterest(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value * 100,
        );
    }

    public function amountInterestDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['amount_interest'] > 0 ? $attributes['amount_interest'] / 100 : 0
        );
    }
}
