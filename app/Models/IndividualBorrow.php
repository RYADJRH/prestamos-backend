<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enum\DayWeekEnum;
use App\Enum\StatePaymentEnum;

class IndividualBorrow extends Model
{
    use HasFactory;
    protected $table        = 'individual_borrows';
    protected $primaryKey   = 'id_borrow';

    protected $guarded = [
        'id_borrow',
        'amount_borrow',
        'amount_interest',
        'day_payment',
        'state_payment'
    ];
    protected $fillable = [
        'created_borrow',
        'id_borrower'
    ];


    protected $casts = [
        'day_payment' => DayWeekEnum::class,
        'state_payment' => StatePaymentEnum::class,
        'created_borrow' => 'date'
    ];

    public function amountBorrow(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value * 100,
        );
    }

    public function amountInterest(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value * 100,
        );
    }

    public function amountBorrowDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['amount_borrow'] > 0 ? ($attributes['amount_borrow'] / 100) : 0,
        );
    }

    public function amountInterestDecimal(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['amount_interest'] > 0 ? ($attributes['amount_interest'] / 100) : 0,
        );
    }

   /*  public function borrower()
    {
        return $this->belongsTo(Borrower::class, 'id_borrower', 'id_borrower');
    }

    public function individual_payments()
    {
        return $this->hasMany(IndividualPayment::class, 'id_borrow', 'id_borrow');
    } */
}
