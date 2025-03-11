<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrivateFileController extends Controller
{
    public function show($path): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {

        // 確保路徑指向 passport 目錄
        $filePath = "passport/{$path}.jpg";

        // 檢查檔案是否存在
        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, '檔案不存在');
        }

        // 返回檔案響應
        return response()->file(Storage::disk('private')->path($filePath));
    }
}
