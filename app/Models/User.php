<?php

namespace App\Models;

use App\Traits\Observable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Observable;

    protected $table        = 'users';
    protected $primaryKey   = 'id_user';
    protected $guarded = [
        'id_user'
    ];
    protected $fillable = [
        'name_user',
        'last_name_user',
        'nick_name_user'
    ];

    protected $hidden = [
        'password_user'
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->password_user;
    }

    public function beneficiarys()
    {
        return $this->hasMany(Beneficiary::class, 'id_user', 'id_user');
    }
}
