<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Ativo;
use App\Models\Setores;
use App\Models\Pessoas;
use App\Models\MovimentoEstoque;
use App\User;
use Illuminate\Http\Request;

class MovimentoEstoqueController extends Controller
{
    public function movimentoEstoque(){
        $loadAtivo = new Ativo;
        $loadAtivo = DB::table('objeto_ativo')
        ->select('id_objeto', 'numero_serie', 'descricao')
        ->whereNull('deleted_at')
        ->get();

        $loadSetores = new Setores;
        $loadSetores = DB::table('setor')
        ->select('id_setor', 'nome')
        ->whereNull('deleted_at')
        ->orderBy('nome','ASC')
        ->get();

        $loadPessoa = new Pessoas;
        $loadPessoa = DB::table('pessoas')
        ->select('id_pessoa', 'nome')
        ->whereRaw("setor = 'Coordenação' OR setor = 'Gerência'")
        ->whereNull('deleted_at')
        ->orderBy('nome','ASC')
        ->get();

        $loadRegister = new User;
        $loadRegister = DB::table('users')
        ->select('id', 'name')
        ->whereNull('deleted_at')
        ->orderBy('name','ASC')
        ->get();

        $loadAtivo = json_decode($loadAtivo, true);

        $loadSetores = json_decode($loadSetores, true);

        $loadPessoa = json_decode($loadPessoa, true);

        $loadRegister = json_decode($loadRegister, true);


        return view('movimentoEstoque',['loadAtivo'=>$loadAtivo, 'loadSetores'=>$loadSetores, 'loadPessoa'=>$loadPessoa, 'loadRegister'=>$loadRegister]);
    }

    public function salvarMovimento(Request $request){
        date_default_timezone_set('America/Sao_Paulo');
        $ativo = $request['MovimentoAtivo'];
        $flagM = $request['flagM'];
        foreach($ativo as $inputAtivo){
            $save = new MovimentoEstoque;
            $save->data_movimento = date('Y-m-d H:i:s',time());
            $save->id_objeto = $inputAtivo;
            $save->id_solicitante = $request['MovimentoSolicitante'];
            $save->id_setor = $request['MovimentoSetor'];
            $save->movimento = $request['MovimentoTipo'];
            $save->id_responsavel = $request['MovimentoResponsavel'];
            $save->motivo = $request['MovimentoMotivo'];
            $save->save();

            if(!empty($inputAtivo)){
                $saveAtivo = Ativo::findOrFail($inputAtivo);
                $saveAtivo->id_setor = $request['MovimentoSetor'];
                $saveAtivo->situacao = $request['MovimentoSituacao'];

                $movGps = $request['Movimentogps'];

                if($movGps != "" || !is_null($movGps)){
                    $saveAtivo->gps = $movGps;
                }
               
                
                $saveAtivo->save();
            }
            
        }

    }

    public function loadRelatorioMovimento(Request $request){

        $mov = $request->input("movimento");

        $movimento = DB::table('movimento_estoque as m')
        ->select('m.id_movimento as id', 'm.motivo as motivo', DB::raw("date_format(m.data_movimento,'%d/%m/%Y às %H:%i:%s') as data"),
        DB::raw("
        (CASE
            WHEN m.movimento = 'empre' THEN 'Empréstimo'
            WHEN m.movimento = 'novaS' THEN 'Nova Solicitação'
            WHEN m.movimento = 'baixa' THEN 'Baixa'
            WHEN m.movimento = 'iNovo' THEN 'Item Novo'
            WHEN m.movimento = 'troca' THEN 'Troca de Posição'
        END) as movimento"), 'o.descricao as descricao', 'o.numero_serie as numero_serie', 'p.nome as solicitante', 's.nome as setor',
        'u.name as responsavel', 
        DB::raw("
        (CASE
           WHEN (SELECT MAX(mm.id_movimento) FROM gestok.movimento_estoque mm WHERE mm.id_objeto = o.id_objeto) = m.id_movimento AND o.emprestimo = 1 THEN 'EMPRESTADO'
           ELSE 'REGULAR'
        END) as emprestimo
        "))
        ->join('gestok.objeto_ativo as o', 'o.id_objeto', '=', 'm.id_objeto')
        ->join('gestok.pessoas as p', 'p.id_pessoa', '=', 'm.id_solicitante')
        ->join('gestok.setor as s', 's.id_setor', '=', 'm.id_setor')
        ->join('gestok.users as u', 'u.id', '=', 'm.id_responsavel');

        /*if($emprestimo==1){
            $movimento = $movimento->whereRaw("(o.emprestimo = ".$emprestimo." AND (SELECT MAX(mm.id_movimento) FROM gestok.movimento_estoque mm WHERE mm.id_objeto = o.id_objeto) = m.id_movimento)");
        }elseif($emprestimo==0){
            $movimento = $movimento->whereRaw("( (o.emprestimo IS NULL || o.emprestimo = 0) || ( o.emprestimo = 1 AND (SELECT MAX(mm.id_movimento) FROM gestok.movimento_estoque mm WHERE mm.id_objeto = o.id_objeto) != m.id_movimento) )");
        }*/

        if($mov != -1){
            $movimento = $movimento->whereRaw("m.movimento = '$mov'");
        }

        $movimento = $movimento->get();

        return json_encode(['data' => $movimento]);

    }

    public function relatorioMovimento(){
        return view ('relatoriosMovimento');
    }
}
