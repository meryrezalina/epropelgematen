<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PdfController extends Controller
{
    public function download_public(Request $request)
    {
        // if(Storage::disk('public')->exists("pdf/$request->file")){
        //     $path = Storage::disk('public')->path("pdf/$request->file");
        //     $content = file_get_contents($path);
        //     return response($content)->withHeaders([
        //         'Content-Type' => mime_content_type($path)
        //     ]);
        // }
        // return redirect('/404');
        $file = public_path() . "/panduan.pdf";

        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response::download($file, "panduan.pdf", $headers);
    }
}
