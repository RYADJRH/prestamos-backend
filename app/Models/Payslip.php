<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payslip extends Model
{
    use HasFactory,sluggable;

    protected $table        = 'payslips';
    protected $primaryKey   = 'id_payslip';

    protected $guarded  = [
        'id_payslip',
        'slug',
    ];
    protected $fillable = [
        'name_payslip',
        'created_payslip',
        'id_group'
    ];

    protected $casts    = [
        'created_payslip' => 'date'
    ];

      /**
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name_payslip'
            ]
        ];
    }

    public function namePayslip(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucfirst($value),
            set: fn ($value) => Str::lower($value),
        );
    }


}
