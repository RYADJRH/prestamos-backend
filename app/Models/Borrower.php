<?php

namespace App\Models;

use App\Traits\BorrowerTraits;
use App\Traits\S3Trait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Borrower extends Model
{
    use HasFactory, BorrowerTraits, S3Trait, Sluggable;

    protected $table        = 'borrowers';
    protected $primaryKey   = 'id_borrower';

    protected $guarded = [
        'id_borrower',
        'slug'
    ];
    protected $fillable = [
        'name_borrower',
        'last_name_borrower',
        'name_file_ine_borrower',
        'name_file_proof_address_borrower',
        'id_beneficiary'
    ];

    protected $hidden = ['name_file_ine_borrower', 'name_file_proof_address_borrower'];
    protected $appends = ['full_name'];

    /**
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name_borrower'
            ]
        ];
    }

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

    public function fullName(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => ucwords($attributes['name_borrower'] . ' ' . $attributes['last_name_borrower']),
        );
    }

    public function nameFileIneBorrowerPath(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $this->getUrlS3("borrowers/{$attributes['id_borrower']}", $attributes['name_file_ine_borrower']),
        );
    }

    public function nameFileProofAddressBorrowerPath(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $this->getUrlS3("borrowers/{$attributes['id_borrower']}", $attributes['name_file_proof_address_borrower']),
        );
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'id_beneficiary', 'id_beneficiary');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, GroupBorrower::class, 'id_borrower', 'id_group');
    }


    public function payments()
    {
        return $this->hasManyThrough(Payment::class, GroupBorrower::class, 'id_borrower', 'id_group_borrower');
    }

}

class BorrowerExtend extends Borrower
{
    protected $appends = ['name_file_ine_borrower_path', 'name_file_proof_address_borrower_path'];
}
