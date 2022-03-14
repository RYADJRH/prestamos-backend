<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Http\Requests\Beneficiary\BeneficiaryRequest;
use Illuminate\Support\Facades\Auth;

class BeneficiaryController extends Controller
{

    public function create(BeneficiaryRequest $request)
    {
        $this->authorize('create', Beneficiary::class);

        $beneficiary = new Beneficiary();
        $beneficiary->name_beneficiary = $request->name_beneficiary;
        $beneficiary->save();
        return response()->json(['success' => true, 'beneficiary' => $beneficiary]);
    }

    public function destroy(Beneficiary $beneficiary)
    {
        $this->authorize('delete', $beneficiary);

        $ifWasDelete = $beneficiary->delete();
        return response()->json(['success' => $ifWasDelete]);
    }

    public function update(BeneficiaryRequest $request, Beneficiary $beneficiary)
    {
        $this->authorize('update', $beneficiary);

        $beneficiary->name_beneficiary  = $request->name_beneficiary;
        $ifwasUpdate                    = $beneficiary->update();

        return response()->json(['success' => $ifwasUpdate, 'beneficiary' => $beneficiary]);
    }

    public function getAll()
    {
        $this->authorize('viewAny', Beneficiary::class);

        $beneficiarys = Auth::user()->beneficiarys->take(5);
        return response()->json(['beneficiarys' => $beneficiarys]);
    }
}
