<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use DataTables;
class ApiOrderObatKandangController extends Controller
{
    private $table = "t_order_obat_kandang";
    private $pk_table = "";

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $data = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_satuan_obat.nama as nama_satuan, m_obat.nama as nama_obat, m_proyek.keterangan as proyek"))
                ->join("m_obat",$this->table.".id_obat","=","m_obat.id")
                ->join("m_proyek",$this->table.".id_proyek","=","m_proyek.id")
                ->leftJoin("m_satuan_obat","m_obat.id_satuan","=","m_satuan_obat.id")
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
            'obat' => 'required',
            'proyek' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        DB::beginTransaction();
        try {
            $pushdata = array(
                "id_obat" => $request->obat,
                "id_proyek" => $request->proyek,
                "jumlah" => str_replace(".","",$request->jumlah),
                "tanggal" => $request->tanggal,
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
            'id' => 'required',
            'obat' => 'required',
            'proyek' => 'required',
            'jumlah' => 'required',
            'tanggal' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        DB::beginTransaction();
        try {
            $pushdata = array(
                "id_obat" => $request->obat,
                "id_proyek" => $request->proyek,
                "jumlah" => str_replace(".","",$request->jumlah),
                "tanggal" => $request->tanggal,
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

    private function save_obat_kandang($data_order,$request){
        $tgl_now = Carbon::now()->isoFormat('YYYY-MM-DD');
        DB::beginTransaction();
        try {
            $pushdata = array(
                "status" => "success",
                "keterangan" => $request->keterangan
            );
            DB::table($this->table)->where("id",$data_order->id)->update($pushdata);
            $new_push_data = array(
                "id_obat" => $data_order->id_obat,
                "id_proyek" => $data_order->id_proyek,
                "id_order" => $data_order->id,
                "jumlah" => $data_order->jumlah,
                "tanggal" => $tgl_now,
                "created_at" => Carbon::now()
            );
            DB::table("t_obat_kandang")->insert($new_push_data);
            $new_mutasi_obat_gudang = array(
                "id_obat" => $data_order->id_obat,
                "mutasi" => "keluar",
                "jumlah" => $data_order->jumlah,
                "tanggal" => $tgl_now,
                "created_at" => Carbon::now()
            );
            DB::table("t_obat_gudang")->insert($new_mutasi_obat_gudang);
            DB::commit();
            return response()->json(['status'=>'success','messages'=>'success'], 200);
        } catch(QueryException $e) { 
            DB::rollback();
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }

    public function approve(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
            'keterangan' => 'required',
        ]);
        $this->pk_table = $request->id;
        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        if($request->status == "success"){
            $data_order = DB::table($this->table)->where("id",$this->pk_table)->first();
            $result = $this->save_obat_kandang($data_order,$request);
            return $result;
        }
    }

    public function show($id){
        $this->pk_table = base64_decode($id);
        $res = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_satuan_obat.nama as nama_satuan, m_obat.nama as nama_obat, m_proyek.keterangan as proyek"))
                ->join("m_obat",$this->table.".id_obat","=","m_obat.id")
                ->join("m_proyek",$this->table.".id_proyek","=","m_proyek.id")
                ->leftJoin("m_satuan_obat","m_obat.id_satuan","=","m_satuan_obat.id")
                ->where($this->table.".id",$this->pk_table)
                ->first();
        return response()->json(["status" => "success", "messages" => "Success", "data" => $res],200);
    }
    
}
