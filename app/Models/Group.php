<?php

namespace App\Models;

use App\Enum\DayWeekEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'id_beneficiary'
    ];

    protected $casts    = [
        'created_group' => 'date',
        'day_payment'   => DayWeekEnum::class
    ];

   /*  public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class,'id_beneficiary','id_beneficiary');
    } */
}
