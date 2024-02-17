<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use DataTables;
class ApiPakanTranferGudangController extends Controller
{
    private $table = "t_pakan_trasnfer_gudang";
    private $table_mutasi = "t_mutasi_pakan_gudang";
    private $table_mutasi_kandang = "t_mutasi_pakan_kandang";
    private $via = "transfer";
    private $pk_table = "";

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $data = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_proyek.keterangan as proyek,m_pakan.nama as pakan"))
                ->join(DB::raw("m_proyek"),"m_proyek.id","=",$this->table.".id_proyek")
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

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'proyek' => 'required',
            'pakan' => 'required',
            'jumlah' => 'required|numeric|digits_between:1,11',
            'status' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'required',
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
                'id_proyek' => $request->proyek,
                'id_pakan' => $request->pakan,
                'jumlah' => $request->jumlah,
                'status' => $request->status,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                "created_at" => Carbon::now()
            );
            $id_transaksi = DB::table($this->table)->insertGetId($pushdata);
            $this->save_to_mutasi_pakan($request,$id_transaksi);
            DB::commit();
            return response()->json(['status'=>'success','messages'=>'success'], 200);
        } catch(QueryException $e) { 
            DB::rollback();
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }

    public function update(Request $request){
        $this->pk_table = base64_decode($request->id);
        $setting = [
            'id' => 'required',
            'proyek' => 'required',
            'pakan' => 'required',
            'status' => 'required',
            'jumlah' => 'required|numeric|digits_between:1,11',
            'tanggal' => 'required',
            'keterangan' => 'required',
        ];
       
        $validator = Validator::make($request->all(), $setting);
        if ($validator->fails()) {
            return response()->json([
                "status"    => "warning",
                "messages"   => $validator->errors()->first(),
            ], 400);
        }
        DB::beginTransaction();
        try {
            $pushdata = array(
                'id_proyek' => $request->proyek,
                'id_pakan' => $request->pakan,
                'status' => $request->status,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                "updated_at" => Carbon::now()
            );
           
            DB::table($this->table)->where("id",$this->pk_table)->update($pushdata);
            $this->save_to_mutasi_pakan($request,$this->pk_table);
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
                ->select($this->table.".*",DB::raw("m_proyek.keterangan as proyek,m_pakan.nama as pakan"))
                ->join(DB::raw("m_proyek"),"m_proyek.id","=",$this->table.".id_proyek")
                ->join("m_pakan","m_pakan.id","=",$this->table.".id_pakan")
                ->first();
        return response()->json(["status" => "success", "messages" => "Success", "data" => $res],200);
    }

    private function save_to_mutasi_pakan($request,$id_transaksi){
        /** MUTASI GUDANG */
        $pushdata = array(
            'id_pakan' => $request->pakan,
            'id_transaksi' => $id_transaksi,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'via' => $this->via,
            'keterangan' => $request->keterangan,
            "created_at" => Carbon::now()
        );
        DB::table($this->table_mutasi)->insert($pushdata);

        $status_kandang = $request->status == "masuk" ? "keluar" : "masuk";
        /** Mutasi Kandang */
        $pushdata = array(
            'id_proyek' => $request->proyek,
            'id_pakan' => $request->pakan,
            'id_transaksi' => $id_transaksi,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'status' => $status_kandang,
            'via' => "gudang",
            'keterangan' => $request->keterangan,
            "created_at" => Carbon::now()
        );
        DB::table($this->table_mutasi_kandang)->insert($pushdata);

        return true;
    }
}
