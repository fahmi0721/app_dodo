<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use DataTables;
class ApiMutasiPakanGudangController extends Controller
{
    private $table = "t_mutasi_pakan_gudang";

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $data = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_pakan.nama as pakan"))
                ->join("m_pakan","m_pakan.id","=",$this->table.".id_pakan")
                ->get();
        return Datatables::of($data)
        ->addIndexColumn()
        // ->addColumn('action', function($row){
        //         $btn = '<div class="btn-group"><a href="'.url("admin/kabupaten/edit").'/'.base64_encode($row->id).'" class="btn btn-primary btn-xs"><i class="fa fa-xs fa-edit"></i></a>';
        //         $btn .= '<a href="javascript:void(0)" onclick=\'hapus_data("'.base64_encode($row->id).'")\' class="btn btn-danger btn-xs"><i class="fa fa-xs fa-trash"></i></a></div>';
        //         return $btn;
        // })
        // ->rawColumns(['action'])
        ->make(true);
    }

    public function show_filter_pakan($pakan_id){
        $data = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_pakan.nama as pakan"))
                ->join("m_pakan","m_pakan.id","=",$this->table.".id_pakan")
                ->where($this->table.".id_pakan",base64_decode($pakan_id))
                ->get();
        return Datatables::of($data)
        ->addIndexColumn()
        // ->addColumn('action', function($row){
        //         $btn = '<div class="btn-group"><a href="'.url("admin/kabupaten/edit").'/'.base64_encode($row->id).'" class="btn btn-primary btn-xs"><i class="fa fa-xs fa-edit"></i></a>';
        //         $btn .= '<a href="javascript:void(0)" onclick=\'hapus_data("'.base64_encode($row->id).'")\' class="btn btn-danger btn-xs"><i class="fa fa-xs fa-trash"></i></a></div>';
        //         return $btn;
        // })
        // ->rawColumns(['action'])
        ->make(true);
    }

    public function show_filter_stok(){
        $data = DB::select("
                SELECT a.id_pakan, 
                (
                    (SELECT IF(SUM(b.jumlah)>0,SUM(b.jumlah),0) FROM t_mutasi_pakan_gudang b WHERE b.`status` = 'masuk' AND b.id_pakan = a.id_pakan) - 
                    (SELECT IF(SUM(c.jumlah)>0,SUM(c.jumlah),0) FROM t_mutasi_pakan_gudang c WHERE c.`status` = 'keluar' AND c.id_pakan = a.id_pakan)
                ) AS stok,
                d.nama as pakan
                FROM t_mutasi_pakan_gudang a
                INNER JOIN m_pakan d ON a.id_pakan = d.id
                GROUP BY a.id_pakan
            ");
        return Datatables::of($data)
        ->addIndexColumn()
        // ->addColumn('action', function($row){
        //         $btn = '<div class="btn-group"><a href="'.url("admin/kabupaten/edit").'/'.base64_encode($row->id).'" class="btn btn-primary btn-xs"><i class="fa fa-xs fa-edit"></i></a>';
        //         $btn .= '<a href="javascript:void(0)" onclick=\'hapus_data("'.base64_encode($row->id).'")\' class="btn btn-danger btn-xs"><i class="fa fa-xs fa-trash"></i></a></div>';
        //         return $btn;
        // })
        // ->rawColumns(['action'])
        ->make(true);
    }

    public function show_filter_stok_by_pakan($pakan_id){
        $data = DB::select("
                SELECT a.id_pakan, 
                (
                    (SELECT IF(SUM(b.jumlah)>0,SUM(b.jumlah),0) FROM t_mutasi_pakan_gudang b WHERE b.`status` = 'masuk' AND b.id_pakan = a.id_pakan) - 
                    (SELECT IF(SUM(c.jumlah)>0,SUM(c.jumlah),0) FROM t_mutasi_pakan_gudang c WHERE c.`status` = 'keluar' AND c.id_pakan = a.id_pakan)
                ) AS stok,
                d.nama as pakan
                FROM t_mutasi_pakan_gudang a
                INNER JOIN m_pakan d ON a.id_pakan = d.id
                WHERE a.id_pakan='".base64_decode($pakan_id)."'
                GROUP BY a.id_pakan
            ");
        return Datatables::of($data)
        ->addIndexColumn()
        // ->addColumn('action', function($row){
        //         $btn = '<div class="btn-group"><a href="'.url("admin/kabupaten/edit").'/'.base64_encode($row->id).'" class="btn btn-primary btn-xs"><i class="fa fa-xs fa-edit"></i></a>';
        //         $btn .= '<a href="javascript:void(0)" onclick=\'hapus_data("'.base64_encode($row->id).'")\' class="btn btn-danger btn-xs"><i class="fa fa-xs fa-trash"></i></a></div>';
        //         return $btn;
        // })
        // ->rawColumns(['action'])
        ->make(true);
    }
}
