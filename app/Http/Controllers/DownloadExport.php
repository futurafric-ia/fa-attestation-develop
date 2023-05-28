<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DownloadExport extends Controller
{
    public function __invoke($file)
    {
        $exportFileName = Storage::disk('public')->path('exports') . '/' . $file;

        if (! File::exists($exportFileName)) {
            return back()->with('error', "Le fichier exporté n'a pas été retrouvé.");
        }

        return response()->download($exportFileName);
    }
}
