<?php

namespace App\Models;

use App\Enum\DayWeekEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Group extends Model
{
    use HasFactory;

    protected $table        = 'groups';
    protected $primaryKey   = 'id_group';

    protected $guarded  = [
        'id_group',
        'day_payment',
    ];

    protected $fillable = [
        'name_group',
        'created_group',
        'state_archived_group',
        'id_beneficiary'
    ];

    protected $casts    = [
        'created_group'         => 'date',
        'state_archived_group'  => 'boolean',
        'day_payment'           => DayWeekEnum::class
    ];

    protected $appends  = ['day_payment_name'];

    public function nameGroup(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => Str::lower($value),
        );
    }

    public function dayPaymentName(): Attribute
    {
        return new Attribute(
            get: fn () => DayWeekEnum::getLabel($this->day_payment),
        );
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'id_beneficiary', 'id_beneficiary');
    }
}
