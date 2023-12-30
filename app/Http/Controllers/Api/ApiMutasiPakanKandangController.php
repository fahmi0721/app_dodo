<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use DataTables;
class ApiMutasiPakanKandangController extends Controller
{
    private $table = "t_mutasi_pakan_kandang";

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $data = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_proyek.keterangan as proyek,m_pakan.nama as pakan"))
                ->join("m_proyek","m_proyek.id","=",$this->table.".id_proyek")
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

    public function show_filter_proyek($proyek_id){
        $id_proyek = base64_decode($proyek_id);
        $data = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_proyek.keterangan as proyek,m_pakan.nama as pakan"))
                ->join("m_proyek","m_proyek.id","=",$this->table.".id_proyek")
                ->join("m_pakan","m_pakan.id","=",$this->table.".id_pakan")
                ->where($this->table.".id_proyek",$id_proyek)
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
                SELECT a.id_proyek, 
                    (
                        (SELECT IF(SUM(b.jumlah)>0,SUM(b.jumlah),0) FROM t_mutasi_pakan_kandang b WHERE b.`status` = 'masuk' AND b.id_proyek = a.id_proyek) - 
                        (SELECT IF(SUM(c.jumlah)>0,SUM(c.jumlah),0) FROM t_mutasi_pakan_kandang c WHERE c.`status` = 'keluar' AND c.id_proyek = a.id_proyek)
                    ) AS stok,
                    d.keterangan as proyek,
                    e.nama as peternak,
                    f.nama as kandang
                FROM t_mutasi_pakan_kandang a
                INNER JOIN m_proyek d ON a.id_proyek = d.id
                LEFT JOIN m_peternak e ON d.id_peternak = e.id
                LEFT JOIN m_kandang f ON d.id_kandang = f.id
                GROUP BY a.id_proyek
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

    public function show_filter_stok_proyek($proyek_id){
        $id_proyek = base64_decode($proyek_id);
        $data = DB::select("
                SELECT a.id_proyek, 
                    (
                        (SELECT IF(SUM(b.jumlah)>0,SUM(b.jumlah),0) FROM t_mutasi_pakan_kandang b WHERE b.`status` = 'masuk' AND b.id_proyek = a.id_proyek) - 
                        (SELECT IF(SUM(c.jumlah)>0,SUM(c.jumlah),0) FROM t_mutasi_pakan_kandang c WHERE c.`status` = 'keluar' AND c.id_proyek = a.id_proyek)
                    ) AS stok,
                    d.keterangan as proyek,
                    e.nama as peternak,
                    f.nama as kandang
                FROM t_mutasi_pakan_kandang a
                INNER JOIN m_proyek d ON a.id_proyek = d.id
                LEFT JOIN m_peternak e ON d.id_peternak = e.id
                LEFT JOIN m_kandang f ON d.id_kandang = f.id
                WHERE a.id_proyek = ".$id_proyek."
                GROUP BY a.id_proyek
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
