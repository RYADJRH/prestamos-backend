<?php

namespace App\Models;

use App\Enum\StatePaymentEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table        = 'payments';
    protected $primaryKey   = 'id_payment';

    protected $guarded  = [
        'id_payment',
    ];
    protected $fillable = [
        'amount_payment',
        'state_payment',
        'created_payment',
        'id_payslip',
        'id_group_borrower'
    ];

    protected $casts    = [
        'created_payment'   => 'date',
        'state_payment'     => StatePaymentEnum::class
    ];
    protected $appends = ['amount_payment_decimal'];

    public function amountPayment(): Attribute
    {
        return new Attribute(
            set: fn ($value) => round($value * 100, 2),
        );
    }

    public function amountPaymentDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['amount_payment'] > 0 ? round($attributes['amount_payment'] / 100, 2) : 0,
        );
    }

    public function borrower()
    {
        return $this->hasOneThrough(Borrower::class, GroupBorrower::class, 'id_group_borrower', 'id_borrower', 'id_group_borrower', 'id_borrower');
    }
}
