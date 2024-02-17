<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ApiKaryawanController;
use App\Http\Controllers\Api\ApiWilayahPenugasanController;
use App\Http\Controllers\Api\ApiMerkPakanController;
use App\Http\Controllers\Api\ApiTipePakanController;
use App\Http\Controllers\Api\ApiVendorPakanController;
use App\Http\Controllers\Api\ApiSatuanObatController;
use App\Http\Controllers\Api\ApiPeternakController;
use App\Http\Controllers\Api\ApiStandarController;
use App\Http\Controllers\Api\ApiObatController;
use App\Http\Controllers\Api\ApiKandangController;
use App\Http\Controllers\Api\ApiPakanController;
use App\Http\Controllers\Api\ApiProyekController;
use App\Http\Controllers\Api\ApiUsersController;
use App\Http\Controllers\Api\ApiPakanMasukKandangController;
use App\Http\Controllers\Api\ApiTransferAntarKandangController;
use App\Http\Controllers\Api\ApiMutasiPakanKandangController;
use App\Http\Controllers\Api\ApiPakanMasukGudangController;
use App\Http\Controllers\Api\ApiPakanTranferGudangController;
use App\Http\Controllers\Api\ApiMutasiPakanGudangController;
use App\Http\Controllers\Api\ApiHargaPakanController;

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

Route::group(['middleware' => 'api', 'prefix' => 'satuan_obat'], function($router){
    Route::post("save",[ApiSatuanObatController::class,'store']);
    Route::put("update",[ApiSatuanObatController::class,'update']);
    Route::delete("delete",[ApiSatuanObatController::class,'destroy']);
    Route::get("get_data/{id}",[ApiSatuanObatController::class,'show']);
    Route::get("/",[ApiSatuanObatController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'peternak'], function($router){
    Route::post("save",[ApiPeternakController::class,'store']);
    Route::put("update",[ApiPeternakController::class,'update']);
    Route::delete("delete",[ApiPeternakController::class,'destroy']);
    Route::get("get_data/{id}",[ApiPeternakController::class,'show']);
    Route::get("/",[ApiPeternakController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'standar'], function($router){
    Route::post("save",[ApiStandarController::class,'store']);
    Route::put("update",[ApiStandarController::class,'update']);
    Route::delete("delete",[ApiStandarController::class,'destroy']);
    Route::get("get_data/{id}",[ApiStandarController::class,'show']);
    Route::get("/",[ApiStandarController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'obat'], function($router){
    Route::post("save",[ApiObatController::class,'store']);
    Route::put("update",[ApiObatController::class,'update']);
    Route::delete("delete",[ApiObatController::class,'destroy']);
    Route::get("get_data/{id}",[ApiObatController::class,'show']);
    Route::get("/",[ApiObatController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'kandang'], function($router){
    Route::post("save",[ApiKandangController::class,'store']);
    Route::put("update",[ApiKandangController::class,'update']);
    Route::delete("delete",[ApiKandangController::class,'destroy']);
    Route::get("get_data/{id}",[ApiKandangController::class,'show']);
    Route::get("/",[ApiKandangController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'pakan'], function($router){
    Route::post("save",[ApiPakanController::class,'store']);
    Route::put("update",[ApiPakanController::class,'update']);
    Route::delete("delete",[ApiPakanController::class,'destroy']);
    Route::get("get_data/{id}",[ApiPakanController::class,'show']);
    Route::get("/",[ApiPakanController::class,'index']);
    Route::get("/harga/{id}",[ApiHargaPakanController::class,'index']);
    Route::post("/harga/{id_pakan}/save",[ApiHargaPakanController::class,'store']);
    Route::put("/harga/{id_pakan}/update",[ApiHargaPakanController::class,'update']);
    Route::delete("/harga/{id_pakan}/delete/",[ApiHargaPakanController::class,'destroy']);
    Route::get("/harga/{id_pakan}/get_data/",[ApiHargaPakanController::class,'show']);
});

Route::group(['middleware' => 'api', 'prefix' => 'proyek'], function($router){
    Route::post("save",[ApiProyekController::class,'store']);
    Route::put("update",[ApiProyekController::class,'update']);
    Route::delete("delete",[ApiProyekController::class,'destroy']);
    Route::get("get_data/{id}",[ApiProyekController::class,'show']);
    Route::get("/",[ApiProyekController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'users'], function($router){
    Route::post("save",[ApiUsersController::class,'store']);
    Route::put("update",[ApiUsersController::class,'update']);
    Route::delete("delete",[ApiUsersController::class,'destroy']);
    Route::get("get_data/{id}",[ApiUsersController::class,'show']);
    Route::get("/",[ApiUsersController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'pakan_masuk_kandang'], function($router){
    Route::post("save",[ApiPakanMasukKandangController::class,'store']);
    Route::put("update",[ApiPakanMasukKandangController::class,'update']);
    Route::delete("delete",[ApiPakanMasukKandangController::class,'destroy']);
    Route::get("get_data/{id}",[ApiPakanMasukKandangController::class,'show']);
    Route::get("/",[ApiPakanMasukKandangController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'pakan_transfer_kandang'], function($router){
    Route::post("save",[ApiTransferAntarKandangController::class,'store']);
    Route::put("update",[ApiTransferAntarKandangController::class,'update']);
    Route::delete("delete",[ApiTransferAntarKandangController::class,'destroy']);
    Route::get("get_data/{id}",[ApiTransferAntarKandangController::class,'show']);
    Route::get("/",[ApiTransferAntarKandangController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'mutasi_pakan_kandang'], function($router){
    Route::get("/",[ApiMutasiPakanKandangController::class,'index']);
    Route::get("/get_filter_by_proyek/{proyek_id}",[ApiMutasiPakanKandangController::class,'show_filter_proyek']);
    Route::get("/get_stok_by_proyek/{proyek_id}",[ApiMutasiPakanKandangController::class,'show_filter_stok_proyek']);
    Route::get("/get_stok",[ApiMutasiPakanKandangController::class,'show_filter_stok']);
});

Route::group(['middleware' => 'api', 'prefix' => 'pakan_masuk_gudang'], function($router){
    Route::post("save",[ApiPakanMasukGudangController::class,'store']);
    Route::put("update",[ApiPakanMasukGudangController::class,'update']);
    Route::delete("delete",[ApiPakanMasukGudangController::class,'destroy']);
    Route::get("get_data/{id}",[ApiPakanMasukGudangController::class,'show']);
    Route::get("/",[ApiPakanMasukGudangController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'pakan_transfer_gudang'], function($router){
    Route::post("save",[ApiPakanTranferGudangController::class,'store']);
    Route::put("update",[ApiPakanTranferGudangController::class,'update']);
    Route::delete("delete",[ApiPakanTranferGudangController::class,'destroy']);
    Route::get("get_data/{id}",[ApiPakanTranferGudangController::class,'show']);
    Route::get("/",[ApiPakanTranferGudangController::class,'index']);
});

Route::group(['middleware' => 'api', 'prefix' => 'mutasi_pakan_gudang'], function($router){
    Route::get("/",[ApiMutasiPakanGudangController::class,'index']);
    Route::get("/get_filter_by_pakan/{pakan_id}",[ApiMutasiPakanGudangController::class,'show_filter_pakan']);
    Route::get("/get_stok_by_pakan/{pakan_id}",[ApiMutasiPakanGudangController::class,'show_filter_stok_by_pakan']);
    Route::get("/get_stok",[ApiMutasiPakanGudangController::class,'show_filter_stok']);
});

