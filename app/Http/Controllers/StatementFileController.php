<?php

namespace App\Http\Controllers;

use App\Models\StatementFile;
use Illuminate\Http\Request;

class StatementFileController extends Controller
{
    //

    public function upload_statement(Request $request)
    {
        $path = $request->file('statement')->store('statements');

        StatementFile::create([
            'original_path' => $path
        ]);
    }
}
