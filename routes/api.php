<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use \App\Http\Controllers\UploadController;
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//文件上传
Route::prefix('upload')->group(function () {
    //文件上传
    Route::any('index', [UploadController::class, 'upload']);
    //文件合并
    Route::any('merge', [UploadController::class, 'mergeFile']);
    //上传前文件检测
    Route::any('beforeUpload', [UploadController::class, 'beforeUpload']);

});
