<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Beneficiary extends Model
{
    use HasFactory;

    protected $table        = 'beneficiaries';
    protected $primaryKey   = 'id_beneficiary';
    protected $guarded = [
        'id_beneficiary'
    ];
    protected $fillable = [
        'name_beneficiary',
        'id_user'
    ];

    protected $appends = [
        'name_acronym'
    ];

    public function nameBeneficiary(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => Str::lower($value),
        );
    }

    public function nameAcronym(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => create_acronym($attributes['name_beneficiary']),
        );
    }

    public function borrowersExtend()
    {
        return $this->hasMany(BorrowerExtend::class, 'id_beneficiary', 'id_beneficiary');
    }

    public function borrowers()
    {
        return $this->hasMany(Borrower::class, 'id_beneficiary', 'id_beneficiary');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'id_beneficiary', 'id_beneficiary');
    }


    public function individualLoans()
    {
        return $this->hasManyThrough(IndividualBorrow::class, Borrower::class, 'id_beneficiary', 'id_borrower');
    }


}
