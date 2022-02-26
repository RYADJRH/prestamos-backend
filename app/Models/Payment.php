<?php

namespace App\Models;

use App\Enum\StatePaymentEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table        = 'payments';
    protected $primaryKey   = 'id_payment';

    protected $guarded  = [
        'id_payment',
        'amount_payment',
        'state_payment',
    ];
    protected $fillable = [
        'created_payment',
        'id_payslip',
        'id_group_borrower'
    ];

    protected $casts    = [
        'created_payment'   => 'date',
        'state_payment'     => StatePaymentEnum::class
    ];

    public function amountPayment(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value * 100,
        );
    }

    public function amountPaymentDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['amount_payment'] > 0 ? $attributes['amount_payment'] / 100 : 0,
        );
    }
}
