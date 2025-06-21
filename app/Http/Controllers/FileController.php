<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
public function servePrivateFile($filename)
{
    $path = 'private/temp_verifications/' . $filename;

    if (!Storage::disk('local')->exists($path)) {
        abort(404, 'File not found: ' . $filename);
    }

    $file = Storage::disk('local')->get($path);
    $mime = Storage::disk('local')->mimeType($path);

    return Response::make($file, 200)
        ->header('Content-Type', $mime)
        ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
}

}
