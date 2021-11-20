<?php

namespace App\Http\Controllers;

use App\Models\TipoAtivo;
use App\Models\Ativo;
use App\Models\Manutencao;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RetornoManutencaoController extends Controller
{
    public function retornoManutencao(){

        return view('retornoManutencao');
    }

    public function loadRetornoManutencao(Request $request){
        $rtn = $request->input("retorno");
        $retorno = date('Y-m-d', strtotime(str_replace('/', '-', $rtn)));
        
        //$loadManutencao = new Ativo;
        $query = DB::table('ativo as a')
        ->select('c.id_manutencao', 'a.nome', 'b.descricao', 'd.nome as estoque', 'b.numero_serie','b.id_objeto', 'b.patrimonio as patrimonio',
        DB::raw("date_format(c.data_envio,'%d/%m/%Y às %H:%i:%s') as envio"))
        ->join('gestok.objeto_ativo as b', 'b.id_ativo', '=', 'a.id_ativo')
        ->join('gestok.manutencao as c', 'c.id_objeto', '=', 'b.id_objeto')
        ->join('gestok.estoque as d','b.id_estoque', '=', 'd.id_estoque')
        ->whereNull('c.data_retorno');
        if($rtn != null){
            $query->whereBetween('c.data_envio', [$retorno." 00:00:00", $retorno." 23:59:59"]);
        }
        $resultado = $query->get();

        return json_encode(['data' => $resultado]);

    }

    public function salvarRetorno(Request $request){
        
        date_default_timezone_set('America/Sao_Paulo');
        $tipo = $request['tipo'];
        
        $id_manutencao = $request['id_manutencao'];

        if($tipo == 0){

            foreach($id_manutencao as $man){
                $manutencao = Manutencao::findOrFail($man);

                $ativo = Ativo::findOrFail($manutencao->id_objeto);
                $ativo->situacao = '3';
                $ativo->save();


                $manutencao->data_retorno = date('Y-m-d H:i:s',time());
                $manutencao->teve_conserto = 'N';
                $manutencao->save();
            }  

        }elseif($tipo ==  1){

            foreach($id_manutencao as $man){
                $manutencao = Manutencao::findOrFail($man);

                $ativo = Ativo::findOrFail($manutencao->id_objeto);
                $ativo->situacao = '1';
                $ativo->save();


                $manutencao->data_retorno = date('Y-m-d H:i:s',time());
                $manutencao->teve_conserto = 'S';
                $manutencao->save();
            }

        }
    }
}