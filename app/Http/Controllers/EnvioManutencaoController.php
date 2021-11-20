<?php

namespace App\Http\Controllers;
use App\Models\TipoAtivo;
use App\Models\Ativo;
use App\Models\Pessoas;
use App\Models\Manutencao;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EnvioManutencaoController extends Controller
{
    public function envioManutencao(){
        $loadTipoAtivo = DB::table('objeto_ativo as a')
        ->select('a.id_objeto', 'a.id_ativo', 'a.id_estoque', 'b.nome as tipo', 'a.descricao', 'a.numero_serie', 'c.nome as estoque', 'a.patrimonio', 'a.gps', 'a.situacao')
        ->join(DB::raw('gestok.ativo as b'),'a.id_ativo', '=', 'b.id_ativo')
        ->join(DB::raw('gestok.estoque as c'),'a.id_estoque', '=', 'c.id_estoque')
        ->where('situacao',2)
        ->whereNull('a.deleted_at')
        ->get();

        $loadPessoa = new Pessoas;
        $loadPessoa = DB::table('pessoas')
        ->select('id_pessoa', 'nome', 'matricula')
        ->whereNull('deleted_at')
        ->whereRaw("setor = 'Coordenação' OR setor = 'Gerência'")
        ->orderBy('nome','ASC')
        ->get();

        $loadTipoAtivo = json_decode($loadTipoAtivo, true);
        $loadTipoAtivo = ['loadTipoAtivo'=>$loadTipoAtivo];
        
        $loadPessoa = json_decode($loadPessoa, true);
        $loadPessoa = ['loadPessoa'=>$loadPessoa];

        return view('envioManutencao', $loadTipoAtivo, $loadPessoa);
    }

    public function loadAtivoManutencao(Request $request){
        $retorno = null;
        /*if(strpos($request->input("ativos"), ",")!==false){
            $retorno = explode(",", $request->input("ativos"));
        }elseif($request->input("ativos")!="null"){
            $retorno[] = $request->input("ativos");
        }*/

        $query =  DB::table('objeto_ativo as a')
        ->select('a.id_objeto', 'a.id_ativo', 'a.id_estoque', 'b.nome as tipo', 'a.descricao', 'a.numero_serie', 'c.nome as estoque', 'a.patrimonio', 'a.gps', 'a.situacao')
        ->join(DB::raw('gestok.ativo as b'),'a.id_ativo', '=', 'b.id_ativo')
        ->join(DB::raw('gestok.estoque as c'),'a.id_estoque', '=', 'c.id_estoque')
        ->where('situacao',2)
        ->whereNull('a.deleted_at')
        ->whereNotIn('a.id_objeto', [DB::raw('(SELECT
            mm.id_objeto
            FROM gestok.manutencao mm
            WHERE mm.id_objeto = a.id_objeto
            AND data_retorno IS NULL
            GROUP BY id_objeto)')]);
        if(is_array($retorno)){
            $query->whereIn('a.id_objeto', $retorno);
        }
        $resultado = $query->get();
        
        //dd($resultado);

        return json_encode(['data' => $resultado]);
    }

    public function salvarEnvio(Request $request){
        date_default_timezone_set('America/Sao_Paulo');
        $EnvioAtivos = $request->input('EnvioAtivos');

        foreach($EnvioAtivos as $inputCheck){
            $save = new Manutencao;
            $save->data_envio = date('Y-m-d H:i:s',time());
            $save->id_objeto = $inputCheck;
            $save->id_responsavel = $request['EnvioResponsavel'];
            $save->save();
        }
        
        
    }
}
