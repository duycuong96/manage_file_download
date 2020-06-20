<?php

namespace App\Http\Controllers;

use App\UploadFile;
use Arr;
use Auth;
use Illuminate\Http\Request;
use Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id = Auth::user()->id;
        $path = $id;
        $list_directorie = Storage::directories($id);
        $list_files = Storage::files($id);

        // $list_object = array_merge($list_directories, $list_files);
        $listDirectorie = array_map(function($item){
            // dd(Storage::mimeType($item));
            return [
                'name' => basename($item),
                'path' => $item,
            ];
        }, $list_directorie);

        $listFile = array_map(function($item){
            // dd(Storage::mimeType($item));
            return [
                'name' => basename($item),
                'path' => $item,
                'size' => Storage::size($item)/1048576,
                'type' => Storage::mimeType($item)
            ];
        }, $list_files);

        return view('home', ['listFile' => $listFile,
                            'listDirectorie' => $listDirectorie,
                            'path' => $path,
                            ]);
    }

}
