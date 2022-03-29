<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait BorrowerTraits
{
    function createFile($name_column, $file)
    {
        $name_file_0 = sha1(date('YmdHis') . Str::random(30)) . '.' . $file->extension();
        Storage::disk('s3')->putFileAs("borrowers/{$this->id_borrower}", $file, $name_file_0);
        $this->update([$name_column => $name_file_0]);
    }
    
    function deleteFile($name_column_file)
    {
        if ($this[$name_column_file]) {
            Storage::disk('s3')->delete("borrowers/{$this->id_borrower}/{$this[$name_column_file]}");
            $this->update([$name_column_file => null]);
        }
    }
}
