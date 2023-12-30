<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use DataTables;
class ApiProyekController extends Controller
{
    private $table = "m_proyek";
    private $pk_table = "";

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $data = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_peternak.nama as peternak"),DB::raw("m_kandang.nama as kandang"),DB::raw("m_karyawan.nama as ppl"))
                ->join("m_peternak",$this->table.".id_peternak","=","m_peternak.id")
                ->join("m_kandang",$this->table.".id_kandang","=","m_kandang.id")
                ->join("m_karyawan",$this->table.".id_ppl","=","m_karyawan.id")
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

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'peternak' => 'required',
            'kandang' => 'required',
            'ppl' => 'required',
            'populasi' => 'required|numeric|digits_between:1,11',
            'tgl_chekin' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        /** Cek Poyek Kandang Aktif */
        $cek = DB::table($this->table)
                ->where([
                    ["id_peternak","=",$request->peternak],
                    ["id_kandang","=",$request->kandang],
                    ["status","=","active"]
                ])
                ->get();
        if($cek->count() > 0){
            return response()->json([
                "status"    => "warning",
                "messages"   => "Proyek ini masih active",
            ], 400);
        }
        DB::beginTransaction();
        try {
            $pushdata = array(
                "id_peternak" => $request->peternak,
                "id_kandang" => $request->kandang,
                "id_ppl" => $request->ppl,
                "populasi" => $request->populasi,
                "tgl_chekin" => $request->tgl_chekin,
                "bw_tiba" => $request->bw_tiba,
                "kode_box" => $request->kode_box,
                "plat_polisi" => $request->plat_polisi,
                "waktu_berangkat" => $request->waktu_berangkat,
                "waktu_tiba" => $request->waktu_tiba,
                "no_spb" => $request->no_spb,
                "driver" => $request->driver,
                "jenis_doc" => $request->jenis_doc,
                "keterangan" => $request->keterangan,
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

    public function update(Request $request){
        $this->pk_table = base64_decode($request->id);
        $validator = Validator::make($request->all(), [
            "id" => "required",
            'peternak' => 'required',
            'kandang' => 'required',
            'ppl' => 'required',
            'populasi' => 'required|numeric|digits_between:1,11',
            'tgl_chekin' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }

        /** Cek Poyek Kandang Aktif */
        $cek = DB::table($this->table)
                ->where([
                    ["id_peternak","=",$request->peternak],
                    ["id_kandang","=",$request->kandang],
                    ["status","=","active"],
                    ["id","!=",$this->pk_table],
                ])
                ->get();
        if($cek->count() > 0){
            return response()->json([
                "status"    => "warning",
                "messages"   => "Proyek ini masih active",
            ], 400);
        }

        DB::beginTransaction();
        try {
            $pushdata = array(
                "id_peternak" => $request->peternak,
                "id_kandang" => $request->kandang,
                "id_ppl" => $request->ppl,
                "populasi" => $request->populasi,
                "tgl_chekin" => $request->tgl_chekin,
                "bw_tiba" => $request->bw_tiba,
                "kode_box" => $request->kode_box,
                "plat_polisi" => $request->plat_polisi,
                "waktu_berangkat" => $request->waktu_berangkat,
                "waktu_tiba" => $request->waktu_tiba,
                "no_spb" => $request->no_spb,
                "driver" => $request->driver,
                "jenis_doc" => $request->jenis_doc,
                "keterangan" => $request->keterangan,
                "updated_at" => Carbon::now()
            );
            DB::table($this->table)->where("id",$this->pk_table)->update($pushdata);
            DB::commit();
            return response()->json(['status'=>'success','messages'=>'success'], 200);
        } catch(QueryException $e) { 
            DB::rollback();
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }

    public function destroy(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        DB::beginTransaction();
        $this->pk_table = base64_decode($request->id);
        try {
            DB::table($this->table)->where("id",$this->pk_table)->delete();
            DB::commit();
            return response()->json(['status'=>'success','messages'=>'success'], 200);
        } catch(QueryException $e) { 
            DB::rollback();
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }

    public function show($id){
        $this->pk_table = base64_decode($id);
        $res = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_peternak.nama as peternak"),DB::raw("m_kandang.nama as kandang"),DB::raw("m_karyawan.nama as ppl"))
                ->join("m_peternak",$this->table.".id_peternak","=","m_peternak.id")
                ->join("m_kandang",$this->table.".id_kandang","=","m_kandang.id")
                ->join("m_karyawan",$this->table.".id_ppl","=","m_karyawan.id")
                ->where($this->table.".id",$this->pk_table)
                ->first();
        return response()->json(["status" => "success", "messages" => "Success", "data" => $res],200);
    }
}
