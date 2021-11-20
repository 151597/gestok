<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\TipoAtivo;
use App\Models\Estoque;
use App\Models\Ativo;
use App\Models\Setores;
use Illuminate\Http\Request;

class AtivoController extends Controller
{
    public function cadastroAtivo(){
        $loadTipoAtivo = new TipoAtivo;
        $loadTipoAtivo = DB::table('ativo')
        ->select('id_ativo', 'nome')
        ->whereNull('deleted_at')
        ->orderBy('nome','ASC')
        ->get();

        $loadEstoque = new Estoque;
        $loadEstoque = DB::table('estoque')
        ->select('id_estoque', 'nome')
         ->whereNull('deleted_at')
        ->orderBy('nome','ASC')
        ->get();

        $loadSetor = new Setores;
        $loadSetor = DB::table('setor')
        ->select('id_setor', 'nome')
        ->whereNull('deleted_at')
        ->orderBy('nome','ASC')
        ->get();

        $loadTipoAtivo = json_decode($loadTipoAtivo, true);

        $loadEstoque = json_decode($loadEstoque, true);

        $loadSetor = json_decode($loadSetor, true);

        return view('cadastroAtivo',['loadTipoAtivo'=>$loadTipoAtivo, 'loadEstoque'=>$loadEstoque, 'loadSetor'=>$loadSetor]);
    }

    public function salvarAtivo(Request $request){
        $ativo = $request['id'];
        if(!empty($ativo)){
            $save = Ativo::findOrFail($ativo);
        }else{
            $save = new Ativo;
        }
        $save->id_ativo = $request['cadastroTipo'];
        $save->id_estoque = $request['cadastroEstoque'];
        $save->gps = $request['cadastroGPS'];
        $save->descricao = $request['cadastroDescricao'];
        $save->numero_serie = strtoupper($request['cadastroSerie']);
        $save->patrimonio = $request['cadastroPatrimonio'];
        $save->situacao = $request['cadastroSituacao'];
		$save->id_setor = $request['cadastroSetor'];
		$save->ramal    = $request['cadastroRamal'];
        $save->save();
    }

    public function loadAtivo(){
        $loadAtivo = new Ativo;
        $loadAtivo = DB::table('objeto_ativo as a')
        ->select('a.id_objeto', 'a.gps', 'a.id_ativo', 'a.id_estoque', 'b.nome as tipo', 'a.descricao', 'a.numero_serie', 'c.nome as estoque', 'a.patrimonio', 'a.gps', 'a.situacao', 'a.id_setor', 'd.nome as nome_setor','a.ramal as ramal')
        ->join(DB::raw('gestok.ativo as b'),'a.id_ativo', '=', 'b.id_ativo')
        ->join(DB::raw('gestok.estoque as c'),'a.id_estoque', '=', 'c.id_estoque')
        ->join(DB::raw('gestok.setor as d'),'a.id_setor', '=', 'd.id_setor')
        //->join(DB::raw('gestok.setor as d'),'a.id_setor', '=', 'd.id_setor')
        ->whereNull('a.deleted_at')
        //->whereNull('d.deleted_at')
        //->whereNull('d.deleted_at')
        ->get();
        return json_encode(['data' => $loadAtivo]);
    }

    public function deleteAtivo(Request $request){
        $idCheck = $request->input('data');
        $deleteTipoAtivo = Ativo::whereIn('id_objeto', $idCheck);
        $deleteTipoAtivo->delete();
    }
}
