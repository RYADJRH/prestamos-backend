<?php

namespace App\Observers;

use App\Models\Borrower;
use Illuminate\Support\Facades\Storage;

class BorrowerObserver
{
    public function deleting(Borrower $borrower)
    {
        Storage::disk('s3')->deleteDirectory("borrowers/{$borrower->id_borrower}");
    }
}
