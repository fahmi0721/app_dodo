<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use DataTables;
class ApiPakanMasukKandangController extends Controller
{
    private $table = "t_pakan_masuk_kandang";
    private $table_mutasi = "t_mutasi_pakan_kandang";
    private $via = "pakan_masuk";
    private $status = "masuk";
    private $pk_table = "";

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $data = DB::table($this->table)
                ->select($this->table.".*",DB::raw("m_pakan.nama as pakan, m_vendor_pakan.nama as vendor, m_proyek.keterangan as proyek,m_peternak.nama as peternak,m_kandang.nama as kandang"))
                ->join("m_pakan",$this->table.".id_pakan","=","m_pakan.id")
                ->join("m_vendor_pakan",$this->table.".id_vendor","=","m_vendor_pakan.id")
                ->join("m_proyek",$this->table.".id_proyek","=","m_proyek.id")
                ->leftJoin("m_kandang","m_proyek.id_kandang","=","m_kandang.id")      
                ->leftJoin("m_peternak","m_proyek.id_peternak","=","m_peternak.id")      
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
            'vendor' => 'required',
            'jumlah' => 'required|numeric|digits_between:1,11',
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
                'id_vendor' => $request->vendor,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                "created_at" => Carbon::now()
            );
            $id_transaksi = DB::table($this->table)->insertGetId($pushdata);
            $this->save_to_mutasi_pakan_kandang($request,$id_transaksi);
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
            'vendor' => 'required',
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
                'id_vendor' => $request->vendor,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                "updated_at" => Carbon::now()
            );
           
            DB::table($this->table)->where("id",$this->pk_table)->update($pushdata);
            $this->save_to_mutasi_pakan_kandang($request,$this->pk_table);
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
                ->select($this->table.".*",DB::raw("m_pakan.nama as pakan, m_vendor_pakan.nama as vendor, m_proyek.keterangan as proyek,m_peternak.nama as peternak,m_kandang.nama as kandang"))
                ->join("m_pakan",$this->table.".id_pakan","=","m_pakan.id")
                ->join("m_vendor_pakan",$this->table.".id_vendor","=","m_vendor_pakan.id")
                ->join("m_proyek",$this->table.".id_proyek","=","m_proyek.id")
                ->leftJoin("m_kandang","m_proyek.id_kandang","=","m_kandang.id")      
                ->leftJoin("m_peternak","m_proyek.id_peternak","=","m_peternak.id")
                ->first();
        return response()->json(["status" => "success", "messages" => "Success", "data" => $res],200);
    }

    private function save_to_mutasi_pakan_kandang($request,$id_transaksi){
        $pushdata = array(
            'id_proyek' => $request->proyek,
            'id_pakan' => $request->pakan,
            'id_transaksi' => $id_transaksi,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'status' => $this->status,
            'via' => $this->via,
            'keterangan' => $request->keterangan,
            "created_at" => Carbon::now()
        );
        
        DB::table($this->table_mutasi)->insert($pushdata);
        return true;
    }
}
