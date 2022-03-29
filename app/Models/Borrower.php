<?php

namespace App\Models;

use App\Traits\BorrowerTraits;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Borrower extends Model
{
    use HasFactory, BorrowerTraits;

    protected $table        = 'borrowers';
    protected $primaryKey   = 'id_borrower';

    protected $guarded = [
        'id_borrower'
    ];
    protected $fillable = [
        'name_borrower',
        'last_name_borrower',
        'name_file_ine_borrower',
        'name_file_proof_address_borrower',
        'id_beneficiary'
    ];

    public function nameBorrower(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => Str::lower($value),
        );
    }

    public function lastNameBorrower(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => Str::lower($value),
        );
    }
    
    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'id_beneficiary', 'id_beneficiary');
    }


    /*


    public function individualBorrow()
    {
        return $this->hasMany(IndividualBorrow::class, 'id_borrower', 'id_borrower');
    } */
}
