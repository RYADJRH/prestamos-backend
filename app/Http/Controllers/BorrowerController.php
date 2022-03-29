<?php

namespace App\Http\Controllers;

use App\Http\Requests\Borrower\BorrowerRequest;
use App\Models\Beneficiary;
use App\Models\Borrower;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class BorrowerController extends Controller
{
    public function create(BorrowerRequest $request): JsonResponse
    {
        $id_beneficiary                     = $request->id_beneficiary;
        $name_borrower                      = $request->name_borrower;
        $last_name_borrower                 = $request->last_name_borrower;
        $name_file_ine_borrower             = $request->file('name_file_ine_borrower', null);
        $name_file_proof_address_borrower   = $request->file('name_file_proof_address_borrower', null);

        $this->authorize('create', [Borrower::class, $id_beneficiary]);

        $borrower = new Borrower();
        $borrower->name_borrower        = $name_borrower;
        $borrower->last_name_borrower   = $last_name_borrower;
        $borrower->id_beneficiary       = $id_beneficiary;
        $borrower->name_file_ine_borrower = null;
        $borrower->name_file_proof_address_borrower = null;
        $borrower->saveOrFail();


        if ($name_file_ine_borrower && $borrower) {
            $borrower->createFile('name_file_ine_borrower', $name_file_ine_borrower);
        }

        if ($name_file_proof_address_borrower && $borrower) {
            $borrower->createFile('name_file_proof_address_borrower', $name_file_proof_address_borrower);
        }


        return new JsonResponse(['borrower' => $borrower]);
    }

    public function getAll(Request $request, Beneficiary $beneficiary): JsonResponse
    {
        $search = $request->input('search', '');
        $this->authorize('viewAny', [Borrower::class, $beneficiary]);
        $borrowers = $beneficiary->borrowers()
            ->where('name_borrower', 'LIKE', "%{$search}%")
            ->simplePaginate(5);
        return new JsonResponse(['borrowers' => $borrowers]);
    }

    public function update(BorrowerRequest $request, Borrower $borrower): JsonResponse
    {
        $id_beneficiary                     = $request->id_beneficiary;
        $name_borrower                      = $request->name_borrower;
        $last_name_borrower                 = $request->last_name_borrower;
        $name_file_ine_borrower             = $request->file('name_file_ine_borrower', null);
        $name_file_proof_address_borrower   = $request->file('name_file_proof_address_borrower', null);

        $remove_file_ine_borrower           = $request->input('remove_file_ine_borrower', false);
        $remove_file_proof_address_borrower = $request->input('remove_file_proof_address_borrower', false);

        $this->authorize('update', [$borrower, $id_beneficiary]);

        $borrower->update([
            'name_borrower'         => $name_borrower,
            'last_name_borrower'    => $last_name_borrower,
        ]);


        if ($remove_file_ine_borrower || $name_file_ine_borrower) {
            $borrower->deleteFile("name_file_ine_borrower");
        }

        if ($remove_file_proof_address_borrower || $name_file_proof_address_borrower) {
            $borrower->deleteFile("name_file_proof_address_borrower");
        }

        if (!$remove_file_ine_borrower && $name_file_ine_borrower) {
            $borrower->createFile('name_file_ine_borrower', $name_file_ine_borrower);
        }

        if (!$remove_file_proof_address_borrower && $name_file_proof_address_borrower) {
            $borrower->createFile('name_file_proof_address_borrower', $name_file_proof_address_borrower);
        }

        return new JsonResponse(['borrower' => $borrower]);
    }

    public function delete(Borrower $borrower): JsonResponse
    {
        $this->authorize('delete', $borrower);
        $borrower->delete();
        return new JsonResponse(['borrower' => $borrower]);
    }
}
