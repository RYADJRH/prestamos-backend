<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualPayment extends Model
{
    use HasFactory;

    protected $table        = 'individual_payments';
    protected $primaryKey   = 'id_payment';

    protected $guarded  = [
        'id_payment',
        'amount_payment',
    ];
    protected $fillable = [
        'created_payment',
        'id_borrow'
    ];

    protected $casts    = [
        'created_payment' => 'date'
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

   /*  public function individual_borrow()
    {
        return $this->belongsTo(IndividualBorrow::class, 'id_borrow', 'id_borrow');
    } */
}
