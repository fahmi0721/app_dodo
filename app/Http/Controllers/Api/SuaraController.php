<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;

use DataTables;


class SuaraController extends Controller
{
    private $table = "t_suara_tps";
    private $pk_table = "";
    public function index(){
        $data = DB::table($this->table)
        ->join("m_kabupaten", $this->table.".id_kabupaten", "=","m_kabupaten.id")
        ->join("m_kecamatan", $this->table.".id_kecamatan", "=","m_kecamatan.id")
        ->join("m_desa", $this->table.".id_desa", "=","m_desa.id")
        ->join("m_tps", $this->table.".id_tps", "=","m_tps.id")
        ->join("m_kandidat", $this->table.".id_kandidat", "=","m_kandidat.id")
        ->select($this->table.".*",DB::raw("m_kabupaten.nama as kabupaten"),DB::raw("m_kecamatan.nama as kecamatan"),DB::raw("m_desa.nama as desa"),DB::raw("m_tps.nama as tps"),DB::raw("m_kandidat.nama as kandidat"))
                ->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('status', function($row){
                if($row->status == "waiting"){
                    $btn = "<label class='badge  bg-warning'>Waiting</label>";
                }elseif($row->status == "valid"){
                    $btn = "<label class='badge  bg-success'>Valid</label>";
                }else{
                    $btn = "<label class='badge bg-danger'>Invalid</label>";
                }
                return $btn;
        })
        ->addColumn('action', function($row){
            $btn = '<div class="btn-group"><a target="_blank" href="'.url($row->bukti).'" class="btn btn-success btn-xs" data-toggle="tooltip" title="Lihat Bukti"><i class="fa fa-xs fa-file"></i></a>';
            if($row->status == "waiting"){
                $btn .= '<a href="'.url("admin/suara/edit").'/'.base64_encode($row->id).'" class="btn btn-primary btn-xs"><i class="fa fa-xs fa-edit"></i></a>';
                $btn .= '<a href="javascript:void(0)" onclick=\'hapus_data("'.base64_encode($row->id).'")\' class="btn btn-danger btn-xs"><i class="fa fa-xs fa-trash"></i></a></div>';
            }elseif($row->status == "invalid"){
                $btn .= '<a href="javascript:void(0)" onclick=\'hapus_data("'.base64_encode($row->id).'")\' class="btn btn-danger btn-xs"><i class="fa fa-xs fa-trash"></i></a></div>';
            }else{
                $btn .= '<a href="javascript:void(0)" onclick=\'update_status("'.base64_encode($row->id).'","invalid")\' title="Batalkan" data-toggle="tooltip" class="btn btn-danger btn-xs"><i class="fa fa-xs fa-times"></i></a></div>';
            }
                return $btn;
        })
        ->rawColumns(['action','status'])
        ->make(true);
    }

    public function index_tps($id_tp){
        $id_tps = base64_decode($id_tp);
        $data = DB::table($this->table)
        ->join("m_kabupaten", $this->table.".id_kabupaten", "=","m_kabupaten.id")
        ->join("m_kecamatan", $this->table.".id_kecamatan", "=","m_kecamatan.id")
        ->join("m_desa", $this->table.".id_desa", "=","m_desa.id")
        ->join("m_tps", $this->table.".id_tps", "=","m_tps.id")
        ->join("m_kandidat", $this->table.".id_kandidat", "=","m_kandidat.id")
        ->select($this->table.".*",DB::raw("m_kabupaten.nama as kabupaten"),DB::raw("m_kecamatan.nama as kecamatan"),DB::raw("m_desa.nama as desa"),DB::raw("m_tps.nama as tps"),DB::raw("m_kandidat.nama as kandidat"))
        ->where($this->table.".id_tps",$id_tps)
                ->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('status', function($row){
                if($row->status == "waiting"){
                    $btn = "<label class='badge  bg-warning'>Waiting</label>";
                }elseif($row->status == "valid"){
                    $btn = "<label class='badge  bg-success'>Valid</label>";
                }else{
                    $btn = "<label class='badge bg-danger'>Invalid</label>";
                }
                return $btn;
        })
        ->addColumn('action', function($row){
            $btn = '<div class="btn-group"><a target="_blank" href="'.url($row->bukti).'" class="btn btn-success btn-xs" data-toggle="tooltip" title="Lihat Bukti"><i class="fa fa-xs fa-file"></i></a>';
            if($row->status == "waiting"){
                $btn .= '<a href="'.url("tps/suara/edit").'/'.base64_encode($row->id).'" class="btn btn-primary btn-xs"><i class="fa fa-xs fa-edit"></i></a>';
                $btn .= '<a href="javascript:void(0)" onclick=\'hapus_data("'.base64_encode($row->id).'")\' class="btn btn-danger btn-xs"><i class="fa fa-xs fa-trash"></i></a></div>';
            }elseif($row->status == "invalid"){
                $btn .= '<a href="javascript:void(0)" onclick=\'hapus_data("'.base64_encode($row->id).'")\' class="btn btn-danger btn-xs"><i class="fa fa-xs fa-trash"></i></a></div>';
            }
                return $btn;
        })
        ->rawColumns(['action','status'])
        ->make(true);
    }

    public function index_kabupaten($id_kab){
        $id_kabupaten = base64_decode($id_kab);
        $data = DB::table($this->table)
        ->join("m_kabupaten", $this->table.".id_kabupaten", "=","m_kabupaten.id")
        ->join("m_kecamatan", $this->table.".id_kecamatan", "=","m_kecamatan.id")
        ->join("m_desa", $this->table.".id_desa", "=","m_desa.id")
        ->join("m_tps", $this->table.".id_tps", "=","m_tps.id")
        ->join("m_kandidat", $this->table.".id_kandidat", "=","m_kandidat.id")
        ->select($this->table.".*",DB::raw("m_kabupaten.nama as kabupaten"),DB::raw("m_kecamatan.nama as kecamatan"),DB::raw("m_desa.nama as desa"),DB::raw("m_tps.nama as tps"),DB::raw("m_kandidat.nama as kandidat"))->where($this->table.".id_kabupaten",$id_kabupaten)
                ->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('status', function($row){
            if($row->status == "waiting"){
                $btn = "<label class='badge  bg-warning'>Waiting</label>";
            }elseif($row->status == "valid"){
                $btn = "<label class='badge  bg-success'>Valid</label>";
            }else{
                $btn = "<label class='badge bg-danger'>Invalid</label>";
            }
            return $btn;
        })
        ->addColumn('bukti', function($row){
            $btn = '<div class="btn-group"><a target="_blank" href="'.url($row->bukti).'" class="btn btn-success btn-xs" data-toggle="tooltip" title="Lihat Bukti"><i class="fa fa-xs fa-file"></i></a>';
                return $btn;
        })
        ->rawColumns(['status','bukti'])
        ->make(true);
    }

    public function index_kecamatan($id_kec){
        $id_kecamatan = base64_decode($id_kec);
        $data = DB::table($this->table)
        ->join("m_kabupaten", $this->table.".id_kabupaten", "=","m_kabupaten.id")
        ->join("m_kecamatan", $this->table.".id_kecamatan", "=","m_kecamatan.id")
        ->join("m_desa", $this->table.".id_desa", "=","m_desa.id")
        ->join("m_tps", $this->table.".id_tps", "=","m_tps.id")
        ->join("m_kandidat", $this->table.".id_kandidat", "=","m_kandidat.id")
        ->select($this->table.".*",DB::raw("m_kabupaten.nama as kabupaten"),DB::raw("m_kecamatan.nama as kecamatan"),DB::raw("m_desa.nama as desa"),DB::raw("m_tps.nama as tps"),DB::raw("m_kandidat.nama as kandidat"))->where($this->table.".id_kecamatan",$id_kecamatan)
                ->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('status', function($row){
            if($row->status == "waiting"){
                $btn = "<label class='badge  bg-warning'>Waiting</label>";
            }elseif($row->status == "valid"){
                $btn = "<label class='badge  bg-success'>Valid</label>";
            }else{
                $btn = "<label class='badge bg-danger'>Invalid</label>";
            }
            return $btn;
        })
        ->addColumn('bukti', function($row){
            $btn = '<div class="btn-group"><a target="_blank" href="'.url($row->bukti).'" class="btn btn-success btn-xs" data-toggle="tooltip" title="Lihat Bukti"><i class="fa fa-xs fa-file"></i></a>';
                return $btn;
        })
        ->rawColumns(['status','bukti'])
        ->make(true);
    }

    public function index_desa($id_des){
        $id_desa = base64_decode($id_des);
        $data = DB::table($this->table)
        ->join("m_kabupaten", $this->table.".id_kabupaten", "=","m_kabupaten.id")
        ->join("m_kecamatan", $this->table.".id_kecamatan", "=","m_kecamatan.id")
        ->join("m_desa", $this->table.".id_desa", "=","m_desa.id")
        ->join("m_tps", $this->table.".id_tps", "=","m_tps.id")
        ->join("m_kandidat", $this->table.".id_kandidat", "=","m_kandidat.id")
        ->select($this->table.".*",DB::raw("m_kabupaten.nama as kabupaten"),DB::raw("m_kecamatan.nama as kecamatan"),DB::raw("m_desa.nama as desa"),DB::raw("m_tps.nama as tps"),DB::raw("m_kandidat.nama as kandidat"))->where($this->table.".id_desa",$id_desa)
                ->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('status', function($row){
            if($row->status == "waiting"){
                $btn = "<label class='badge  bg-warning'>Waiting</label>";
            }elseif($row->status == "valid"){
                $btn = "<label class='badge  bg-success'>Valid</label>";
            }else{
                $btn = "<label class='badge bg-danger'>Invalid</label>";
            }
            return $btn;
        })
        ->addColumn('bukti', function($row){
            $btn = '<div class="btn-group"><a target="_blank" href="'.url($row->bukti).'" class="btn btn-success btn-xs" data-toggle="tooltip" title="Lihat Bukti"><i class="fa fa-xs fa-file"></i></a>';
                return $btn;
        })
        ->rawColumns(['status','bukti'])
        ->make(true);
    }

    public function show_approve($id_kab){
        $id_kabupaten = base64_decode($id_kab);
        $data = DB::table($this->table)
        ->join("m_kabupaten", $this->table.".id_kabupaten", "=","m_kabupaten.id")
        ->join("m_kecamatan", $this->table.".id_kecamatan", "=","m_kecamatan.id")
        ->join("m_desa", $this->table.".id_desa", "=","m_desa.id")
        ->join("m_tps", $this->table.".id_tps", "=","m_tps.id")
        ->join("m_kandidat", $this->table.".id_kandidat", "=","m_kandidat.id")
        ->select($this->table.".*",DB::raw("m_kabupaten.nama as kabupaten"),DB::raw("m_kecamatan.nama as kecamatan"),DB::raw("m_desa.nama as desa"),DB::raw("m_tps.nama as tps"),DB::raw("m_kandidat.nama as kandidat"))->where($this->table.".id_kabupaten",$id_kabupaten)->where($this->table.".status","waiting")
                ->get();
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $btn = '<div class="btn-group"><a target="_blank" href="'.url($row->bukti).'" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Lihat Bukti"><i class="fa fa-xs fa-file"></i></a>';
            $btn .= '<a href="javascript:void(0)" onclick=\'update_status("'.base64_encode($row->id).'","valid")\' title="Setujui" data-toggle="tooltip" class="btn btn-success btn-xs"><i class="fa fa-xs fa-check"></i></a>';
            $btn .= '<a href="javascript:void(0)" onclick=\'update_status("'.base64_encode($row->id).'","invalid")\' title="Batalkan" data-toggle="tooltip" class="btn btn-danger btn-xs"><i class="fa fa-xs fa-times"></i></a></div>';
                return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    
    public function show_notif($id_kab){
        try {
            $id_kabupaten = base64_decode($id_kab);
            $res = DB::table($this->table)->select(DB::raw("COUNT(id) as tot"))->where("id_kabupaten",$id_kabupaten)->where("status","waiting")->first();
            return response()->json(['status'=>'success','messages'=>'success','data' => $res], 200);
        } catch(QueryException $e) { 
            DB::rollback();
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }

    }

    private function uploadImg($request,$filename,$folder){
        if(!File::exists("dokumen-file")){
            File::makeDirectory("dokumen-file", 0777, true, true);
        }

        if(!File::exists("dokumen-file/gambar_".$folder)){
            File::makeDirectory("dokumen-file/gambar_".$folder, 0777, true, true);
        }
        $request->bukti->move("dokumen-file/gambar_".$folder."/",$filename);
        return "dokumen-file/gambar_".$folder."/".$filename;

    }
    public function rekapitulasi(Request $request)
    {
        $data = array();
        $validator = Validator::make($request->all(), [
            'berdasarkan' => 'required',
        ]);
        $berdsarkan = $request->berdasarkan;
        if($berdsarkan == "kecamatan"){
            $validator = Validator::make($request->all(), [
                'id_kabupaten' => 'required',
            ]);
        }elseif($berdsarkan == "desa"){
            $validator = Validator::make($request->all(), [
                'id_kabupaten' => 'required',
                'id_kecamatan' => 'required',
            ]);
        }elseif($berdsarkan == "tps"){
            $validator = Validator::make($request->all(), [
                'id_kabupaten' => 'required',
                'id_kecamatan' => 'required',
                'id_desa' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        try {
            $data = $this->get_data_suara($berdsarkan,$request);

            return response()->json(['status'=>'success','messages'=>'success','data' => $data], 200);
        } catch(QueryException $e) { 
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }
   
    private function get_data_suara($berdsarkan, $request){
        $data = array();
        if($berdsarkan == "kabupaten"){
            $dt_kab = DB::table("m_kabupaten")->select("id","nama")->get();
            $labels = array();
            $dataset = array();
            foreach($dt_kab as $kab){
                $labels[] = $kab->nama;
                /** PEMILIH PASTI */
                $data_pemilih_pasti = DB::table("t_pemilih_pasti")->select(DB::raw("COUNT(id) as tot"))->where("id_kabupaten",$kab->id)->first();
                $data['pemilih_pasti'][] = $data_pemilih_pasti->tot;

                $data_suara_ayp = DB::table("t_suara_tps")->select(DB::raw("SUM(t_suara_tps.total_suara) as tot"))->join("m_kandidat","m_kandidat.id","=","t_suara_tps.id_kandidat")->where("m_kandidat.kategori","caleg")->where("t_suara_tps.id_kabupaten",$kab->id)->groupBy("t_suara_tps.id_kabupaten")->first();
                $data['suara_ayp'][] = is_null($data_suara_ayp) ? 0 : (int) $data_suara_ayp->tot;

                $data_pemilih = DB::table("t_suara_tps")->select(DB::raw("SUM(t_suara_tps.jumlah_pemilih) as tot"))->join("m_kandidat","m_kandidat.id","=","t_suara_tps.id_kandidat")->where("m_kandidat.kategori","caleg")->where("t_suara_tps.id_kabupaten",$kab->id)->groupBy("t_suara_tps.id_kabupaten")->first();
                $data['data_pemilih'][] = is_null($data_pemilih) ? 0 : (int) $data_pemilih->tot;
            }
            $data['labels'] = $labels;
            return $data;
        }elseif($berdsarkan == "kecamatan"){
            $id_kab = $request->id_kabupaten;
            $dt_kec = DB::table("m_kecamatan")->select("id","nama")->where("id_kabupaten",$id_kab)->get();
            $labels = array();
            $dataset = array();
            foreach($dt_kec as $dt){
                $labels[] = $dt->nama;
                /** PEMILIH PASTI */
                $data_pemilih_pasti = DB::table("t_pemilih_pasti")->select(DB::raw("COUNT(id) as tot"))->where("id_kecamatan",$dt->id)->first();
                $data['pemilih_pasti'][] = $data_pemilih_pasti->tot;

                $data_suara_ayp = DB::table("t_suara_tps")->select(DB::raw("SUM(t_suara_tps.total_suara) as tot"))->join("m_kandidat","m_kandidat.id","=","t_suara_tps.id_kandidat")->where("m_kandidat.kategori","caleg")->where("t_suara_tps.id_kecamatan",$dt->id)->groupBy("t_suara_tps.id_kecamatan")->first();
                $data['suara_ayp'][] = is_null($data_suara_ayp) ? 0 : (int) $data_suara_ayp->tot;

                $data_pemilih = DB::table("t_suara_tps")->select(DB::raw("SUM(t_suara_tps.jumlah_pemilih) as tot"))->join("m_kandidat","m_kandidat.id","=","t_suara_tps.id_kandidat")->where("m_kandidat.kategori","caleg")->where("t_suara_tps.id_kecamatan",$dt->id)->groupBy("t_suara_tps.id_kecamatan")->first();
                $data['data_pemilih'][] = is_null($data_pemilih) ? 0 : (int) $data_pemilih->tot;
            }
            $data['labels'] = $labels;
            return $data;
        }elseif($berdsarkan == "desa"){
            $id_kec = $request->id_kecamatan;
            $dt_des = DB::table("m_desa")->select("id","nama")->where("id_kecamatan",$id_kec)->get();
            $labels = array();
            $dataset = array();
            foreach($dt_des as $dt){
                $labels[] = $dt->nama;
                /** PEMILIH PASTI */
                $data_pemilih_pasti = DB::table("t_pemilih_pasti")->select(DB::raw("COUNT(id) as tot"))->where("id_desa",$dt->id)->first();
                $data['pemilih_pasti'][] = $data_pemilih_pasti->tot;

                $data_suara_ayp = DB::table("t_suara_tps")->select(DB::raw("SUM(t_suara_tps.total_suara) as tot"))->join("m_kandidat","m_kandidat.id","=","t_suara_tps.id_kandidat")->where("m_kandidat.kategori","caleg")->where("t_suara_tps.id_desa",$dt->id)->groupBy("t_suara_tps.id_desa")->first();
                $data['suara_ayp'][] = is_null($data_suara_ayp) ? 0 : (int) $data_suara_ayp->tot;

                $data_pemilih = DB::table("t_suara_tps")->select(DB::raw("SUM(t_suara_tps.jumlah_pemilih) as tot"))->join("m_kandidat","m_kandidat.id","=","t_suara_tps.id_kandidat")->where("m_kandidat.kategori","caleg")->where("t_suara_tps.id_desa",$dt->id)->groupBy("t_suara_tps.id_desa")->first();
                $data['data_pemilih'][] = is_null($data_pemilih) ? 0 : (int) $data_pemilih->tot;
            }
            $data['labels'] = $labels;
            return $data;
        }elseif($berdsarkan == "tps"){
            $id_desa = $request->id_desa;
            $dt_tps = DB::table("m_tps")->select("id","nama")->where("id_desa",$id_desa)->get();
            $labels = array();
            $dataset = array();
            foreach($dt_tps as $dt){
                $labels[] = $dt->nama;
                /** PEMILIH PASTI */
                $data_pemilih_pasti = DB::table("t_pemilih_pasti")->select(DB::raw("COUNT(id) as tot"))->where("id_tps",$dt->id)->first();
                $data['pemilih_pasti'][] = $data_pemilih_pasti->tot;

                $data_suara_ayp = DB::table("t_suara_tps")->select(DB::raw("SUM(t_suara_tps.total_suara) as tot"))->join("m_kandidat","m_kandidat.id","=","t_suara_tps.id_kandidat")->where("m_kandidat.kategori","caleg")->where("t_suara_tps.id_tps",$dt->id)->groupBy("t_suara_tps.id_tps")->first();
                $data['suara_ayp'][] = is_null($data_suara_ayp) ? 0 : (int) $data_suara_ayp->tot;

                $data_pemilih = DB::table("t_suara_tps")->select(DB::raw("SUM(t_suara_tps.jumlah_pemilih) as tot"))->join("m_kandidat","m_kandidat.id","=","t_suara_tps.id_kandidat")->where("m_kandidat.kategori","caleg")->where("t_suara_tps.id_tps",$dt->id)->groupBy("t_suara_tps.id_tps")->first();
                $data['data_pemilih'][] = is_null($data_pemilih) ? 0 : (int) $data_pemilih->tot;
            }
            $data['labels'] = $labels;
            return $data;
        }

    }

    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kandidat' => 'required',
            'id_kabupaten' => 'required',
            'id_kecamatan' => 'required',
            'id_desa' => 'required',
            'id_tps' => 'required',
            'bukti' => 'required',
            'suara_kandidat' => 'required|integer',
            'jumlah_pemilih' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }

        /** IMAGE PROSES */
        $filename = "bukti_".time().".".$request->bukti->extension();
        $folder = "bukti";
        $bukti = $this->uploadImg($request,$filename,$folder);
        if(!$bukti){
            return response()->json([
                "status"    => "warning",
                "messages"   => "Terjadi kesalahan mengupload dokumen",
            ], 400);
        }
        
        DB::beginTransaction();
        try {
            $pushdata = array(
                "id_kabupaten" => $request->id_kabupaten,
                "id_kecamatan" => $request->id_kecamatan,
                "id_desa" => $request->id_desa,
                "id_tps" => $request->id_tps,
                "id_kandidat" => $request->id_kandidat,
                "total_suara" => $request->suara_kandidat,
                "jumlah_pemilih" => $request->jumlah_pemilih,
                "bukti" => $bukti,
                "created_at" => Carbon::now()
            );
            DB::table($this->table)->insert($pushdata);
            DB::commit();
            return response()->json(['status'=>'success','messages'=>'success'], 200);
        } catch(QueryException $e) { 
            DB::rollback();
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }

    }

    public function show($id)
    {
        $this->pk_table = base64_decode($id);
        $res = DB::table($this->table)
                ->join("m_kabupaten", $this->table.".id_kabupaten", "=","m_kabupaten.id")
                ->join("m_kecamatan", $this->table.".id_kecamatan", "=","m_kecamatan.id")
                ->join("m_desa", $this->table.".id_desa", "=","m_desa.id")
                ->join("m_tps", $this->table.".id_tps", "=","m_tps.id")
                ->join("m_kandidat", $this->table.".id_kandidat", "=","m_kandidat.id")
                ->select($this->table.".*",DB::raw("m_kabupaten.nama as kabupaten"),DB::raw("m_kecamatan.nama as kecamatan"),DB::raw("m_desa.nama as desa"),DB::raw("m_tps.nama as tps"),DB::raw("m_kandidat.nama as kandidat"))
                ->where($this->table.".id",$this->pk_table)->first();
        return response()->json(["status" => "success", "message" => "Success", "data" => $res],200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'id_kandidat' => 'required',
            'id_kabupaten' => 'required',
            'id_kecamatan' => 'required',
            'id_desa' => 'required',
            'id_tps' => 'required',
            'suara_kandidat' => 'required|integer',
            'jumlah_pemilih' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        if(!empty($request->bukti)){
            /** IMAGE PROSES */
            $filename = "bukti_".time().".".$request->bukti->extension();
            $folder = "bukti";
            $bukti = $this->uploadImg($request,$filename,$folder);
            if(!$bukti){
                return response()->json([
                    "status"    => "warning",
                    "messages"   => "Terjadi kesalahan mengupload dokumen",
                ], 400);
            }
        }
        DB::beginTransaction();
        try {
            $this->pk_table = base64_decode($request->id);
            $pushdata = array(
                "id_kabupaten" => $request->id_kabupaten,
                "id_kecamatan" => $request->id_kecamatan,
                "id_desa" => $request->id_desa,
                "id_tps" => $request->id_tps,
                "id_kandidat" => $request->id_kandidat,
                "total_suara" => $request->suara_kandidat,
                "jumlah_pemilih" => $request->jumlah_pemilih,
                "updated_at" => Carbon::now()
            );
            if(!empty($request->bukti)){
                $pushdata += array("bukti" => $bukti);
                $this->delete_img($this->pk_table);
            }
            DB::table($this->table)->where("id",$this->pk_table)->update($pushdata);
            DB::commit();
            return response()->json(['status'=>'success','messages'=>'success'], 200);
        } catch(QueryException $e) { 
            DB::rollback();
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }

    public function approve(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        DB::beginTransaction();
        try {
            $this->pk_table = base64_decode($request->id);
            $pushdata = array(
                "status" => $request->status,
            );
            DB::table($this->table)->where("id",$this->pk_table)->update($pushdata);
            DB::commit();
            return response()->json(['status'=>'success','messages'=>'success'], 200);
        } catch(QueryException $e) { 
            DB::rollback();
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }

    private function delete_img($id_data){
        $data = DB::table($this->table)->select("bukti")->where("id",$id_data)->first();
        if(!empty($data->bukti) && File::exists($data->bukti)){
            unlink($data->bukti);
        }
    }

    public function destroy($id)
    {
        $this->pk_table = base64_decode($id);
        DB::beginTransaction();
        try {
            $this->delete_img($this->pk_table);
            DB::table($this->table)->where("id",$this->pk_table)->delete();
            DB::commit();
            return response()->json(['status'=>'success','messages'=>'success'], 200);
        } catch(QueryException $e) { 
            DB::rollback();
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }
}
