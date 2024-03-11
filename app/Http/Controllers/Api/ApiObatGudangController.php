<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use DataTables;
class ApiObatGudangController extends Controller
{
    private $table = "t_obat_gudang";
    private $pk_table = "";

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $data = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_satuan_obat.nama as satuan, m_obat.nama as nama_obat, m_obat.kemasan"))
                ->join("m_obat",$this->table.".id_obat","=","m_obat.id")
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
            'mutasi' => 'required',
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
                "mutasi" => $request->mutasi,
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
            'mutasi' => 'required',
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
                "mutasi" => $request->mutasi,
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

    public function show($id){
        $this->pk_table = base64_decode($id);
        $res = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_satuan_obat.nama as satuan, m_obat.nama as nama_obat, m_obat.kemasan"))
                ->join("m_obat",$this->table.".id_obat","=","m_obat.id")
                ->leftJoin("m_satuan_obat","m_obat.id_satuan","=","m_satuan_obat.id")
                ->where($this->table.".id",$this->pk_table)
                ->first();
        return response()->json(["status" => "success", "messages" => "Success", "data" => $res],200);
    }
}
