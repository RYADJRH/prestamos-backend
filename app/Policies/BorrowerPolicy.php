<?php

namespace App\Policies;

use App\Models\Borrower;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class BorrowerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, $beneficiary)
    {
        return  $beneficiary->user->id_user == $user->id_user;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Borrower $borrower)
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
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Borrower $borrower, $id_beneficiary)
    {
        $beneficiary = $user->beneficiarys()->select('id_beneficiary')->where('id_beneficiary', $id_beneficiary)->first();
        if ($beneficiary)
            return $beneficiary->id_beneficiary == $borrower->id_beneficiary;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Borrower $borrower)
    {
        $beneficiary        = $borrower->beneficiary;
        return  $beneficiary->id_user == $user->id_user;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Borrower $borrower)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Borrower $borrower)
    {
        //
    }
}
