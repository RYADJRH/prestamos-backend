<?php

namespace App\Observers;

use App\Models\Beneficiary;
use Illuminate\Support\Facades\Auth;

class BeneficiaryObserver
{
    public function creating(Beneficiary $beneficiary)
    {
        $beneficiary->id_user = Auth::user()->id_user;
    }
}
