<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use URL;

class UploadController extends Controller
{
    public function create()
    {
        return view('test');

    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            try {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $file->move(public_path('uploads'), $fileName);
                // set data
                // $image = new Post;
                // $image->filename = $fileName;
                // $image->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Upload Successful',
                    'url' => URL::to('uploads') . '/' . $fileName
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload failed'
                ]);
            }
        }
    }
}
