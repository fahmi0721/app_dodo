<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Web\Admin\HomeController;
use App\Http\Controllers\Web\Admin\KabupatenController;
use App\Http\Controllers\Web\Admin\KecamatanController;
use App\Http\Controllers\Web\Admin\DesaController;
use App\Http\Controllers\Web\Admin\TpsController;
use App\Http\Controllers\Web\Admin\PartaiController;
use App\Http\Controllers\Web\Admin\KandidatController;
use App\Http\Controllers\Web\Admin\UsersController;
use App\Http\Controllers\Web\Admin\PemilihController;
use App\Http\Controllers\Web\Admin\SuaraController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function(){
    if(Auth()->user()){
        return redirect('/'.Auth()->user()->level);
    }else{
        return redirect('/login');
    }
});
Route::get('/login', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login'])->name("login");
Route::post('/logout', [AuthController::class, 'logout'])->name("logout");

Route::get('/handle', function(){
        return redirect('/'.Auth()->user()->level);
})->middleware("auth");

Route::prefix('admin')->middleware("auth")->group(function () {
    Route::get('/',[HomeController::class, 'index']);

    Route::prefix('kabupaten')->group(function () {
        Route::get('/',[KabupatenController::class, 'index']);
        Route::get('/tambah',[KabupatenController::class, 'form_add']);
        Route::get('/edit/{id}',[KabupatenController::class, 'form_edit']);
    });

    Route::prefix('kecamatan')->group(function () {
        Route::get('/',[KecamatanController::class, 'index']);
        Route::get('/tambah',[KecamatanController::class, 'form_add']);
        Route::get('/edit/{id}',[KecamatanController::class, 'form_edit']);
    });

    Route::prefix('desa')->group(function () {
        Route::get('/',[DesaController::class, 'index']);
        Route::get('/tambah',[DesaController::class, 'form_add']);
        Route::get('/edit/{id}',[DesaController::class, 'form_edit']);
    });

    Route::prefix('tps')->group(function () {
        Route::get('/',[TpsController::class, 'index']);
        Route::get('/tambah',[TpsController::class, 'form_add']);
        Route::get('/edit/{id}',[TpsController::class, 'form_edit']);
    });

    Route::prefix('partai')->group(function () {
        Route::get('/',[PartaiController::class, 'index']);
        Route::get('/tambah',[PartaiController::class, 'form_add']);
        Route::get('/edit/{id}',[PartaiController::class, 'form_edit']);
    });

    Route::prefix('kandidat')->group(function () {
        Route::get('/',[KandidatController::class, 'index']);
        Route::get('/tambah',[KandidatController::class, 'form_add']);
        Route::get('/edit/{id}',[KandidatController::class, 'form_edit']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/',[UsersController::class, 'index']);
        Route::get('/tambah',[UsersController::class, 'form_add']);
        Route::get('/edit/{id}',[UsersController::class, 'form_edit']);
    });

    Route::prefix('pemilih')->group(function () {
        Route::get('/',[PemilihController::class, 'index']);
        Route::get('/tambah',[PemilihController::class, 'form_add']);
        Route::get('/edit/{id}',[PemilihController::class, 'form_edit']);
    });

    Route::prefix('suara')->group(function () {
        Route::get('/',[SuaraController::class, 'index']);
        Route::get('/tambah',[SuaraController::class, 'form_add']);
        Route::get('/edit/{id}',[SuaraController::class, 'form_edit']);
    });

    Route::prefix('rekap_suara')->group(function () {
        Route::get('/', function(){
            return view("admin.rekap_suara.index");
        });
    });


});

Route::prefix('kabupaten')->middleware("auth")->group(function () {
    Route::get('/',[HomeController::class, 'index']);
    Route::prefix('suara')->group(function () {
        Route::get("/approve", function (){
            return view("kabupaten.suara.approve");
        });
        Route::get("/", function (){
            return view("kabupaten.suara.index");
        });
        ;
    });

    Route::prefix('pemilih')->group(function () {
        Route::get("/", function (){
            return view("kabupaten.pemilih.index");
        });
        Route::get("/tambah", function (){
            $kecamatans = DB::table("m_kecamatan")->select("id","nama")->where("id_kabupaten",auth()->user()->id_kabupaten)->get();
            return view("kabupaten.pemilih.tambah",compact("kecamatans"));
        });
        Route::get("/edit/{id}", function (){
            $kecamatans = DB::table("m_kecamatan")->select("id","nama")->where("id_kabupaten",auth()->user()->id_kabupaten)->get();
            return view("kabupaten.pemilih.edit",compact("kecamatans"));
        });
    });

    Route::prefix('users')->group(function () {
        Route::get("/", function (){
            return view("kabupaten.users.index");
        });
        Route::get("/tambah", function (){
            $kecamatans = DB::table("m_kecamatan")->select("nama","id")->where("id_kabupaten",auth()->user()->id_kabupaten)->get();
            $levels = array('kecamatan','desa','tps');
            return view("kabupaten.users.tambah",compact("kecamatans","levels"));
        });
        Route::get("/edit/{id}", function (){
            $kecamatans = DB::table("m_kecamatan")->select("nama","id")->where("id_kabupaten",auth()->user()->id_kabupaten)->get();
            $levels = array('kecamatan','desa','tps');
            return view("kabupaten.users.edit",compact("kecamatans","levels"));
        });
    });
    Route::prefix('kecamatan')->group(function () {
        Route::get("/", function (){
            return view("kabupaten.kecamatan.index");
        });
        Route::get("/tambah", function (){
            return view("kabupaten.kecamatan.tambah");
        });
        Route::get("/edit/{id}", function (){
            return view("kabupaten.kecamatan.edit");
        });
    });
    Route::prefix('tps')->group(function () {
        Route::get("/", function (){
            return view("kabupaten.tps.index");
        });
        Route::get("/tambah", function (){
            $kecamatans = DB::table("m_kecamatan")->select("nama","id")->where("id_kabupaten",auth()->user()->id_kabupaten)->get();
            return view("kabupaten.tps.tambah",compact("kecamatans"));
        });
        Route::get("/edit/{id}", function (){
            $kecamatans = DB::table("m_kecamatan")->select("nama","id")->where("id_kabupaten",auth()->user()->id_kabupaten)->get();
            return view("kabupaten.tps.edit",compact("kecamatans"));
        });
    });
    Route::prefix('desa')->group(function () {
        Route::get("/", function (){
            return view("kabupaten.desa.index");
        });
        Route::get("/tambah", function (){
            $kecamatans = DB::table("m_kecamatan")->select("nama","id")->where("id_kabupaten",auth()->user()->id_kabupaten)->get();
            return view("kabupaten.desa.tambah",compact("kecamatans"));
        });
        Route::get("/edit/{id}", function (){
            $kecamatans = DB::table("m_kecamatan")->select("nama","id")->where("id_kabupaten",auth()->user()->id_kabupaten)->get();
            return view("kabupaten.desa.edit",compact("kecamatans"));
        });
    });
});

Route::prefix('kecamatan')->middleware("auth")->group(function () {
    Route::get('/',[HomeController::class, 'index']);
    Route::prefix('users')->group(function () {
        Route::get("/", function (){
            return view("kecamatan.users.index");
        });
        Route::get("/tambah", function (){
            $desas = DB::table("m_desa")->select("nama","id")->where("id_kecamatan",auth()->user()->id_kecamatan)->get();
            $levels = array('desa','tps');
            return view("kecamatan.users.tambah",compact("desas","levels"));
        });
        Route::get("/edit/{id}", function (){
            $desas = DB::table("m_desa")->select("nama","id")->where("id_kecamatan",auth()->user()->id_kecamatan)->get();
            $levels = array('desa','tps');
            return view("kecamatan.users.edit",compact("desas","levels"));
        });
    });
    
    Route::prefix('tps')->group(function () {
        Route::get("/", function (){
            return view("kecamatan.tps.index");
        });
        Route::get("/tambah", function (){
            $desas = DB::table("m_desa")->select("nama","id")->where("id_kecamatan",auth()->user()->id_kecamatan)->get();
            return view("kecamatan.tps.tambah",compact("desas"));
        });
        Route::get("/edit/{id}", function (){
            $desas = DB::table("m_desa")->select("nama","id")->where("id_kecamatan",auth()->user()->id_kecamatan)->get();
            return view("kecamatan.tps.edit",compact("desas"));
        });
    });
    
    Route::prefix('desa')->group(function () {
        Route::get("/", function (){
            return view("kecamatan.desa.index");
        });
        Route::get("/tambah", function (){
            return view("kecamatan.desa.tambah");
        });
        Route::get("/edit/{id}", function (){
            return view("kecamatan.desa.edit");
        });
    });

    Route::prefix('suara')->group(function () {
        Route::get("/", function (){
            return view("kecamatan.suara.index");
        });
        ;
    });
    
});

Route::prefix('desa')->middleware("auth")->group(function () {
    Route::get('/',[HomeController::class, 'index']);
    Route::prefix('users')->group(function () {
        Route::get("/", function (){
            
            return view("desa.users.index");
        });
        Route::get("/tambah", function (){
            $levels = array('tps');
            $tpss = DB::table("m_tps")->select("id","nama")->where("id_desa",auth()->user()->id_desa)->get();
            return view("desa.users.tambah",compact("levels","tpss"));
        });
        Route::get("/edit/{id}", function (){
            $levels = array('tps');
            $tpss = DB::table("m_tps")->select("id","nama")->where("id_desa",auth()->user()->id_desa)->get();
            return view("desa.users.edit",compact("levels","tpss"));
        });
    });
    
    Route::prefix('tps')->group(function () {
        Route::get("/", function (){
            return view("desa.tps.index");
        });
        Route::get("/tambah", function (){
            return view("desa.tps.tambah");
        });
        Route::get("/edit/{id}", function (){
            return view("desa.tps.edit");
        });
    });

    Route::prefix('pemilih')->group(function () {
        Route::get("/", function (){
            return view("desa.pemilih.index");
        });
        Route::get("/tambah", function (){
            $tpss = DB::table("m_tps")->select("id","nama")->where("id_desa",auth()->user()->id_desa)->get();
            return view("desa.pemilih.tambah",compact("tpss"));
        });
        Route::get("/edit/{id}", function (){
            $tpss = DB::table("m_tps")->select("id","nama")->where("id_desa",auth()->user()->id_desa)->get();
            return view("desa.pemilih.edit",compact("tpss"));
        });
    });
    
    Route::prefix('suara')->group(function () {
        Route::get("/", function (){
            return view("desa.suara.index");
        });
        ;
    });
});

Route::prefix('tps')->middleware("auth")->group(function () {
    Route::get('/',[HomeController::class, 'index']);
    Route::prefix('suara')->group(function () {
        Route::get("/", function (){
            return view("tps.suara.index");
        });
        Route::get("/tambah", function (){
            $kandidats = DB::table("m_kandidat")->select("nama","id")->get();
            return view("tps.suara.tambah",compact("kandidats"));
        });
        Route::get("/edit/{id}", function (){
            $kandidats = DB::table("m_kandidat")->select("nama","id")->get();
            return view("tps.suara.edit",compact("kandidats"));
        });

    });
});

Route::get('/home', function(){
    return view("home");
})->middleware("auth");

Route::get('/change_photo', function() {
    return view('ganti_foto');
})->middleware('auth');

Route::get('/statistik',[HomeController::class, 'dashboard_statistik']);
Route::get('/progres_suara',[HomeController::class, 'dashboard_progres_suara']);
Route::get('/perolehan_suara',[HomeController::class, 'dashboard_perolehan_suara']);
Route::get('/dwonload',[HomeController::class, 'dowload_data']);
