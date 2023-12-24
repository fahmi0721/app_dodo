<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KabupatenController;
use App\Http\Controllers\Api\KecamatanContoller;
use App\Http\Controllers\Api\DesaContoller;
use App\Http\Controllers\Api\TpsContoller;
use App\Http\Controllers\Api\PartaiContoller;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KandidatController;
use App\Http\Controllers\Api\PemilihController;
use App\Http\Controllers\Api\SuaraController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('kabupaten')->group(function () {
    Route::get('/',[KabupatenController::class, 'index']);
    Route::get('/get_data/{id}',[KabupatenController::class, 'show']);
    Route::get('/get_data_kab',[KabupatenController::class, 'show_kab']);

    Route::post('/save',[KabupatenController::class, 'store']);
    Route::put('/update',[KabupatenController::class, 'update']);
    Route::delete('/delete/{id}',[KabupatenController::class, 'destroy']);
});

Route::prefix('kecamatan')->group(function () {
    Route::get('/',[KecamatanContoller::class, 'index']);
    Route::get('/kabupaten/{id}',[KecamatanContoller::class, 'index_kabupaten']);
    Route::get('/get_data/{id}',[KecamatanContoller::class, 'show']);
    Route::get('/get_data_by_kab/{id}',[KecamatanContoller::class, 'show_by_kab']);
    Route::post('/save',[KecamatanContoller::class, 'store']);
    Route::put('/update',[KecamatanContoller::class, 'update']);
    Route::delete('/delete/{id}',[KecamatanContoller::class, 'destroy']);
});


Route::prefix('kel-desa')->group(function () {
    Route::get('/',[DesaContoller::class, 'index']);
    Route::get('/kabupaten/{id}',[DesaContoller::class, 'index_kabupaten']);
    Route::get('/kecamatan/{id}',[DesaContoller::class, 'index_kecamatan']);
    Route::get('/get_data/{id}',[DesaContoller::class, 'show']);
    Route::get('/get_data_by_kec/{id}',[DesaContoller::class, 'show_by_kec']);
    Route::post('/save',[DesaContoller::class, 'store']);
    Route::put('/update',[DesaContoller::class, 'update']);
    Route::delete('/delete/{id}',[DesaContoller::class, 'destroy']);
});

Route::prefix('tps')->group(function () {
    Route::get('/',[TpsContoller::class, 'index']);
    Route::get('/kabupaten/{id}',[TpsContoller::class, 'index_kabupaten']);
    Route::get('/kecamatan/{id}',[TpsContoller::class, 'index_kecamatan']);
    Route::get('/desa/{id}',[TpsContoller::class, 'index_desa']);
    Route::get('/get_data/{id}',[TpsContoller::class, 'show']);
    Route::get('/get_data_by_des/{id}',[TpsContoller::class, 'show_by_desa']);
    Route::post('/save',[TpsContoller::class, 'store']);
    Route::put('/update',[TpsContoller::class, 'update']);
    Route::delete('/delete/{id}',[TpsContoller::class, 'destroy']);
});

Route::prefix('partai')->group(function () {
    Route::get('/',[PartaiContoller::class, 'index']);
    Route::get('/get_data/{id}',[PartaiContoller::class, 'show']);
    Route::post('/save',[PartaiContoller::class, 'store']);
    Route::put('/update',[PartaiContoller::class, 'update']);
    Route::delete('/delete/{id}',[PartaiContoller::class, 'destroy']);
});

Route::prefix('user')->group(function () {
    Route::get('/',[UserController::class, 'index']);
    Route::get('/kabupaten/{id_kabupaten}',[UserController::class, 'index_kabupaten']);
    Route::get('/kecamatan/{id_kecamatan}',[UserController::class, 'index_kecamatan']);
    Route::get('/desa/{id_desa}',[UserController::class, 'index_desa']);
    Route::get('/get_data/{id}',[UserController::class, 'show']);
    Route::post('/save',[UserController::class, 'store']);
    Route::put('/update',[UserController::class, 'update']);
    Route::put('/update_foto',[UserController::class, 'update_foto']);
    Route::delete('/delete/{id}',[UserController::class, 'destroy']);
});

Route::prefix('kandidat')->group(function () {
    Route::get('/',[KandidatController::class, 'index']);
    Route::get('/get_data/{id}',[KandidatController::class, 'show']);
    Route::post('/save',[KandidatController::class, 'store']);
    Route::put('/update',[KandidatController::class, 'update']);
    Route::delete('/delete/{id}',[KandidatController::class, 'destroy']);
});

Route::prefix('pemilih-pasti')->group(function () {
    Route::get('/',[PemilihController::class, 'index']);
    Route::get('/desa/{id}',[PemilihController::class, 'index_desa']);
    Route::get('/kabupaten/{id}',[PemilihController::class, 'index_kabupaten']);
    Route::get('/get_data/{id}',[PemilihController::class, 'show']);
    Route::post('/save',[PemilihController::class, 'store']);
    Route::put('/update',[PemilihController::class, 'update']);
    Route::delete('/delete/{id}',[PemilihController::class, 'destroy']);
});

Route::prefix('suara')->group(function () {
    Route::get('/',[SuaraController::class, 'index']);
    Route::post('/rekap',[SuaraController::class, 'rekapitulasi']);
    Route::get('/kabupaten/{id_kabupaten}',[SuaraController::class, 'index_kabupaten']);
    Route::get('/kecamatan/{id_kecamatan}',[SuaraController::class, 'index_kecamatan']);
    Route::get('/desa/{id_desa}',[SuaraController::class, 'index_desa']);
    Route::get('/tps/{id_tps}',[SuaraController::class, 'index_tps']);
    Route::get('/get_approve/{id_kabupaten}',[SuaraController::class, 'show_approve']);
    Route::get('/get_data/{id}',[SuaraController::class, 'show']);
    Route::get('/get_notif/{id_kab}',[SuaraController::class, 'show_notif']);
    Route::post('/save',[SuaraController::class, 'store']);
    Route::put('/update',[SuaraController::class, 'update']);
    Route::put('/approve',[SuaraController::class, 'approve']);
    Route::delete('/delete/{id}',[SuaraController::class, 'destroy']);
});
