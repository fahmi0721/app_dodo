<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ApiKaryawanController;
use App\Http\Controllers\Api\ApiWilayahPenugasanController;
use App\Http\Controllers\Api\ApiMerkPakanController;
use App\Http\Controllers\Api\ApiTipePakanController;
use App\Http\Controllers\Api\ApiVendorPakanController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get("/",function(){
    return response()->json(['status' => "error", "messages" => "Session Expired"]);
})->name("login");

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function($router){
    Route::post("register",[AuthController::class,'register'])->name("register");
    Route::post("login",[AuthController::class,'login']);
    Route::post("logout",[AuthController::class,'logout']);
    Route::post("refresh",[AuthController::class,'refresh']);
    Route::post("me",[AuthController::class,'me']);
});

Route::group(['middleware' => 'api', 'prefix' => 'karyawan'], function($router){
    Route::post("save",[ApiKaryawanController::class,'store']);
    Route::put("update",[ApiKaryawanController::class,'update']);
    Route::delete("delete",[ApiKaryawanController::class,'destroy']);
    Route::get("get_data/{id}",[ApiKaryawanController::class,'show']);
    Route::get("/",[ApiKaryawanController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'wilayah_penugasan'], function($router){
    Route::post("save",[ApiWilayahPenugasanController::class,'store']);
    Route::put("update",[ApiWilayahPenugasanController::class,'update']);
    Route::delete("delete",[ApiWilayahPenugasanController::class,'destroy']);
    Route::get("get_data/{id}",[ApiWilayahPenugasanController::class,'show']);
    Route::get("/",[ApiWilayahPenugasanController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'merk_pakan'], function($router){
    Route::post("save",[ApiMerkPakanController::class,'store']);
    Route::put("update",[ApiMerkPakanController::class,'update']);
    Route::delete("delete",[ApiMerkPakanController::class,'destroy']);
    Route::get("get_data/{id}",[ApiMerkPakanController::class,'show']);
    Route::get("/",[ApiMerkPakanController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'tipe_pakan'], function($router){
    Route::post("save",[ApiTipePakanController::class,'store']);
    Route::put("update",[ApiTipePakanController::class,'update']);
    Route::delete("delete",[ApiTipePakanController::class,'destroy']);
    Route::get("get_data/{id}",[ApiTipePakanController::class,'show']);
    Route::get("/",[ApiTipePakanController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'vendor_pakan'], function($router){
    Route::post("save",[ApiVendorPakanController::class,'store']);
    Route::put("update",[ApiVendorPakanController::class,'update']);
    Route::delete("delete",[ApiVendorPakanController::class,'destroy']);
    Route::get("get_data/{id}",[ApiVendorPakanController::class,'show']);
    Route::get("/",[ApiVendorPakanController::class,'index']);
});
