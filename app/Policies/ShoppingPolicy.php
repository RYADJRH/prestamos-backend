<?php

namespace App\Policies;

use App\Models\Shopping;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShoppingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopping  $shopping
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Shopping $shopping)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, $id_beneficiary)
    {
        $beneficiary = $user->beneficiarys()->select('id_beneficiary')->where('id_beneficiary', $id_beneficiary)->first();
        return $beneficiary;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopping  $shopping
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Shopping $shopping)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopping  $shopping
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Shopping $shopping)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopping  $shopping
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Shopping $shopping)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopping  $shopping
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Shopping $shopping)
    {
        //
    }
}
