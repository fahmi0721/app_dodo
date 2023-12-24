<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class HomeController extends Controller
{
    public function index(){
        return view("admin.home");
    }

    public function dashboard_statistik(){
        $total_tps = DB::table("m_tps")->select(DB::raw("COUNT(id) as tot"))->first();
        $total_pemilih = DB::table("t_pemilih_pasti")->select(DB::raw("COUNT(id) as tot"))->first();
        $total_saksi = DB::table("users")->select(DB::raw("COUNT(id) as tot"))->where("level","tps")->first();
        try {
            $data['total_tps'] = $total_tps->tot;
            $data['total_pemilih'] = $total_pemilih->tot;
            $data['total_saksi'] = $total_saksi->tot;
            return response()->json(['status'=>'success','messages'=>'success', "data" => $data], 200);
        } catch (QueryException $e) {
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }

    public function dashboard_progres_suara(){
        $suara_masuk_tps = DB::table(DB::raw('t_suara_tps a'))->join(DB::raw('m_kandidat b'),"a.id_kandidat","=","b.id")->where(DB::raw("b.kategori"),"caleg")->where(DB::raw("a.status"),"valid")->distinct()->count(DB::raw("a.id_tps"));
        $tot_tps = DB::table("users")->where("level","tps")->distinct()->count("id");
        try {
            $data['total_suara_mausk'] = $tot_tps > 0 ? ($suara_masuk_tps/$tot_tps)*100 : 0;
            $data['total_suara_belum_mausk'] = $tot_tps > 0  ? (($tot_tps - $suara_masuk_tps) / $tot_tps) * 100 : 100;
            return response()->json(['status'=>'success','messages'=>'success', "data" => $data], 200);
        } catch (QueryException $e) {
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }

    private function generate_data($data){
        $labels = array();
        $values = array();
        $bg_color = array();
        $posisi =0;
        foreach ($data as  $dt) {
            $labels[] = $dt->nama;
            $values[] = $dt->tot_suara;
            if($posisi == 0){
                $bg_color[] = "rgb(45, 47, 147)";
            }else{
                $bg_color[] = $this->randomHex();
            }
            $posisi++;
        }
        $res['labels'] = $labels;
        $res['values'] = $values;
        $res['bg_color'] = $bg_color;
        return $res;
    }

    private function randomHex() {
        $chars = 'ABCDEF0123456789';
        $color = '#';
        for ( $i = 0; $i < 6; $i++ ) {
           $color .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $color;
     }

    
     public function dashboard_perolehan_suara(){
        $data = DB::table("t_suara_tps")->join("m_kandidat","t_suara_tps.id_kandidat","=","m_kandidat.id")->select("m_kandidat.id","m_kandidat.nama",DB::raw("SUM(t_suara_tps.total_suara) as tot_suara"))->distinct()->where("t_suara_tps.status","valid")->groupBy("t_suara_tps.id_kandidat")->orderBy("m_kandidat.id","ASC")->get();
        $get_data = $this->generate_data($data);
        try {
            return response()->json(['status'=>'success','messages'=>'success', "data" => $get_data], 200);
        } catch (QueryException $e) {
            return response()->json(['status'=>'error','messages'=> $e->errorInfo[2] ], 400);
        }
    }

    public function dowload_data(){
        
    }
}
