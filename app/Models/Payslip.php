<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;

    protected $table        = 'payslips';
    protected $primaryKey   = 'id_payslip';

    protected $guarded  = [
        'id_payslip'
    ];
    protected $fillable = [
        'name_payslip',
        'created_payslip',
        'id_group'
    ];

    protected $casts    = [
        'created_payslip' => 'date'
    ];

    
}
