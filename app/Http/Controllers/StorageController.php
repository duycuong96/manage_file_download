<?php

namespace App\Http\Controllers;

use App\UploadFile;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Url;

class StorageController extends Controller
{

    public function storeFolder()
    {

        $idUser = Auth::user()->id;
        $folderName = request()->name;
        $path = request()->path;
        Storage::makeDirectory($path . '/' . $folderName);

        return redirect()->back()->with('status', 'Thêm thư mục thành công');
    }
    public function destroyFolder()
    {
        $path = urldecode(request()->path);
        // dd($path);
        Storage::deleteDirectory($path);
        return redirect()->back()->with('status', 'Xóa thư mục thành công');
    }
    public function showFolder()
    {
        $path = request()->path;
        //    dd($path);
        $id = Auth::user()->id;

        $list_directorie = Storage::directories($path);
        $list_files = Storage::files($path);
        // $list_object = array_merge($list_directories, $list_files);

        $listDirectorie = array_map(function ($item) {
            return [
                'name' => basename($item),
                'path' => $item,
            ];
        }, $list_directorie);

        $listFile = array_map(function ($item) {
            return [
                'name' => basename($item),
                'path' => $item,
                'size' => Storage::size($item) / 1048576,
                'type' => Storage::mimeType($item)
            ];
        }, $list_files);

        return view('home', [
            'listFile' => $listFile,
            'listDirectorie' => $listDirectorie,
            'path' => $path
        ]);
    }

    public function uploadFile(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        // if ($_FILES['file']['size'] >= 536870912) {
        //     return redirect()->round('home')->with('status', 'file quá lớn');
        // }
        $userId = Auth::user()->id;

        $file = $request->file('file');
        $path = $request->path;
        // dd($path);
        $uploadFile = new UploadFile();
        $uploadFile->type = $file->getMimeType();
        $uploadFile->name = $file->getClientOriginalName();
        $uploadFile->size = number_format($file->getSize() / 1048576, 2);
        $uploadFile->user_id = $userId;

        // Lưu file vào file public có thể hiện thị ảnh
        // $uploadFile->file = $file->storeAs($userId,   $uploadFile->name, 'public');
        // Private thư mục upload
        $uploadFile->file = $file->storeAs($path, $uploadFile->name);

        // $uploadFile->save();
        return redirect()->back()->with('status', 'Upload file thành công');
    }

    public function destroyFile()
    {
        $path = urldecode(request()->path);
        // dd($path);
        Storage::delete($path);
        return redirect()->back()->with('status', 'Xóa file thành công');
    }

    public function download()
    {
        // dd(request('path'));
        // dump(request('path'));
        return Storage::download(request('path'));
    }

    public function checkName()
    {
        // dd(request()->path);
        $userId = Auth::user()->id;

        $name = request()->name;
        $path = request()->path;
        // dd($path);

        if (Storage::exists($path . '/' . $name) == true) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function moveFile(){
        $pathFile = request()->pathFile;
        // $nameFile = request()->nameFile;
        // dd($pathFile);
        $pathDirectorie = request()->pathDirectorie;
        // dd($pathDirectorie);
        if (Storage::exists($pathDirectorie . '/' . basename($pathFile)) == true) {
            return response()->json(['success' => true]);
        } else {
            Storage::move($pathFile, $pathDirectorie . '/' . basename($pathFile));
            return response()->json(['success' => false]);
        }
    }
}
