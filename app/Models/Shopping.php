<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shopping extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "shoppings";
    protected $primaryKey = 'id_shopping';


    protected $guarded  = [
        'id_shopping',
        'state_payment',
    ];

    protected $fillable = [
        'product_name',
        'producto_price',
        'date_shopping',
    ];

    protected $casts    = [
        'date_shopping'   => 'date:Y-m-d',
        'producto_price'  => 'float'
    ];
}
