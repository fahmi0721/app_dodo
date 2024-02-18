<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use DataTables;
class ApiHargaObatController extends Controller
{
    private $table = "m_harga_obat";
    private $pk_table = "";

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index($id_obat){
        $id = base64_decode($id_obat);
        $data = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_obat.nama"))
                ->join("m_obat",$this->table.".id_obat","=","m_obat.id")
                ->where(DB::raw($this->table.".id_obat"),"=",$id)
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

    public function store(Request $request,$id_obat){
        $validator = Validator::make($request->all(), [
            'harga' => 'required',
            'valid_from' => 'required',
            'valid_to' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        $harga_convert = str_replace(".","",$request->harga);
        $id_obat = base64_decode($id_obat);
        DB::beginTransaction();
        try {
            $pushdata = array(
                "id_obat" => $id_obat,
                "harga" => $harga_convert,
                "valid_from" => $request->valid_from,
                "valid_to" => $request->valid_to,
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

    public function update(Request $request,$id_obat){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'harga' => 'required',
            'valid_from' => 'required',
            'valid_to' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        $harga_convert = str_replace(".","",$request->harga);
        $id_obat = base64_decode($id_obat);
        $this->pk_table = base64_decode($request->id);
        DB::beginTransaction();
        try {
            $pushdata = array(
                "id_obat" => $id_obat,
                "harga" => $harga_convert,
                "valid_from" => $request->valid_from,
                "valid_to" => $request->valid_to,
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

    public function show(Request $request){
        $this->pk_table = base64_decode($request->id);
        $res = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_obat.nama"))
                ->join("m_obat",$this->table.".id_obat","=","m_obat.id")
                ->where($this->table.".id",$this->pk_table)
                ->first();
        return response()->json(["status" => "success", "messages" => "Success", "data" => $res],200);
    }
}
