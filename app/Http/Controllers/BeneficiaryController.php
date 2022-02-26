<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Http\Requests\Beneficiary\BeneficiaryRequest;

class BeneficiaryController extends Controller
{

    public function create(BeneficiaryRequest $request)
    {
        $beneficiary = new Beneficiary();
        $beneficiary->name_beneficiary = $request->name_beneficiary;
        $beneficiary->save();
        return response()->json(['success' => true, 'beneficiary' => $beneficiary]);
    }
    public function destroy(Beneficiary $beneficiary)
    {
        $ifWasDelete = $beneficiary->delete();
        return response()->json(['success' => $ifWasDelete]);
    }
}
