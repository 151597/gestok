<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Armario;
use App\Models\OperadoresArmarios;
use App\Models\Operadores;
use Illuminate\Http\Request;
use DateTime;


class ArmarioController extends Controller
{
    public function relacaoArmarios(){

        $ops = new Operadores;
        $usuarios = $ops->opsArmarios();

        $armarios = Armario::select("numeracao")->where("ativo", 1)
        ->whereNull('deleted_at')->orderBy('numeracao','ASC')->get();

        return view("relacionamentoArmarios", ['usuarios' => $usuarios, 'armarios' => $armarios]);
    }

    public function usuariosCP(){
        
        $ops = new Operadores;
        $operadores = $ops->opsArmarios();

        return json_encode(['data' => $operadores]);
    }

    public function salvarRelacionamentoArmario(Request $request){
        $retorno = null;
        $id_relacionamento = $request->input('id_relacionamento');
        $armario = $request->input('cadastroArmario');
        $usuario = $request->input('cadastroUsuario');

        //verifica se trata-se de uma edição ou inclusão
        if(is_null($id_relacionamento)){

            //verifica quantos armários iguais já foram relacionados
            $relConta = OperadoresArmarios::select('id_ops_armarios')->where('id_armario', $armario)->count();

            //se já houver dois relacionamentos, bloqueia mais relacionamentos, evitando excesso de relacionamento por armário
            if($relConta >= 2){
                $retorno = "ERRO! É PERMITIDO APENAS DOIS FUNCIONÁRIOS POR ARMÁRIO!";
            }else{

                //caso constrário, verifica se este usuário já foi relacionado anteriormente com um armário
                $relBusca = OperadoresArmarios::select('id_ops_armarios')
                ->where('id_usuario', $usuario)->first();

                if(is_null($relBusca)){ 
                    $relacionamento = new OperadoresArmarios;
                    $relacionamento->id_usuario = $usuario;
                    $relacionamento->nome = $request->input('nome');
                    $relacionamento->supervisor = $request->input('supervisor');
                    $relacionamento->entrada = $request->input('entrada');
                    $relacionamento->saida = $request->input('saida');
                    $relacionamento->id_armario = $armario;
                    
                    $relacionamento->save();
                    $retorno = "SALVO COM SUCESSO!";
                }else{
                    $retorno = "ESTE FUNCIONÁRIO JÁ ESTÁ RELACIONADO COM UM ARMÁRIO!";
                }
            }
        }else{

            //caso trata-se de edição, busca o registro 
            $relacionamento = OperadoresArmarios::find($id_relacionamento);

            //verifica o armário cadastrado vs. o armário selecionado
            $relConta = OperadoresArmarios::select('id_ops_armarios')
            ->where('id_armario', '!=', $relacionamento->id_armario)
            ->where('id_armario', $armario)
            ->count();

            if($relConta >= 2){
                $retorno = "ERRO! É PERMITIDO APENAS DOIS FUNCIONÁRIOS POR ARMÁRIO!";
            }else{

                $relBusca = OperadoresArmarios::select('id_usuario')
                ->where('id_usuario', $usuario)->first();

                //verifica se o usuário já está relacionado, evitando contar o registro que está sendo editado
                if(is_null($relBusca) || $relBusca->id_usuario == $relacionamento->id_usuario){

                    $relacionamento->id_usuario = $usuario;
                    $relacionamento->nome = $request->input('nome');
                    $relacionamento->supervisor = $request->input('supervisor');
                    $relacionamento->entrada = $request->input('entrada');
                    $relacionamento->saida = $request->input('saida');
                    $relacionamento->id_armario = $armario;
                    
                    $relacionamento->save();
                    $retorno = "SALVO COM SUCESSO!";
                }else{
                    $retorno = "ESTE FUNCIONÁRIO JÁ ESTÁ RELACIONADO COM UM ARMÁRIO!";
                }
            }

        }
        return json_encode($retorno);
    }

    public function loadRelacionamentoArmario(){
        $relacionamento = DB::table('operadores_armarios as op')
        ->select('id_ops_armarios','id_usuario', 'nome', 'supervisor', 'entrada', 'saida', 'id_armario')
        ->join('armarios as ar', 'op.id_armario', '=', 'ar.numeracao')
        ->whereNull('ar.deleted_at')
        ->get();

        return json_encode(['data' => $relacionamento]);
    }

    public function deleteRelacionamento(Request $request){
        $idCheck = $request->input('data');
        $relacionamento = OperadoresArmarios::whereIn('id_ops_armarios', $idCheck);
        $relacionamento->delete();
    }

    public function cadastroArmarios(){
        return view("cadastroArmarios");
    }

    public function salvarArmario(Request $request){

        $numeracao = $request->input('numeracao');
        $arm = Armario::withTrashed()->find($numeracao);
        
        if(is_null($arm)){
            $armario = new Armario;
        }else{
            $armario = $arm;
            $armario->deleted_at = null;
        }

        $armario->numeracao = $numeracao;
        $armario->ativo = intval($request->input('ativo'));
        $armario->save();
    }

    public function loadArmarios(){
        $armario = Armario::select('numeracao','ativo as ativoReal',
        DB::raw("(CASE WHEN ativo = 1 THEN 'Ativo' WHEN ativo = 0 THEN 'Não Ativo' END) as ativo"))->whereNull('deleted_at')->orderBy('numeracao','ASC')->get();

        return json_encode(['data' => $armario]);
    }

    public function deleteArmario(Request $request){
        $idCheck = $request->input('data');
        $armario = Armario::whereIn('numeracao', $idCheck);
        $armario->delete();
    }

    public function relatorioDeFalhas(){

        $relacionamento1 = OperadoresArmarios::select('id_ops_armarios','id_usuario', 'nome', 'entrada', 'saida', 'id_armario')->get();
        $relacionamento2 = OperadoresArmarios::select('id_ops_armarios','id_usuario', 'nome', 'entrada', 'saida', 'id_armario')->get();
        $arrRelacionamentos = [];
        $arrVeri = [];
        foreach($relacionamento1 as $r1){
            $entrada1 = new DateTime($r1->entrada);
            $saida1 = new DateTime($r1->saida);

            foreach($relacionamento2 as $r2){
                $entrada2 = new DateTime($r2->entrada);
                $saida2 = new DateTime($r2->saida);

                if($r1->id_armario == $r2->id_armario && $r1->id_ops_armarios != $r2->id_ops_armarios){
                               
                    if(($entrada2 >= $entrada1 && $entrada2 <= $saida1) || ($saida2 >= $entrada1 && $saida2 <= $saida1)
                    || ($entrada1 >= $entrada2 && $entrada1 <= $saida2) || ($saida1 >= $entrada2 && $saida1 <= $saida2) 
                    || ($entrada1 == $entrada2) || ($saida1 == $saida2) ){
                        if(!in_array($r1, $arrVeri) || !in_array($r2, $arrVeri)){
                            array_push($arrRelacionamentos, ['rel2'=>$r2, 'rel1'=>$r1]);
                            array_push($arrVeri, $r2);
                            array_push($arrVeri, $r1);
                        }
                    }

                }

            }

            
        }
        return view('relatorioFalhas', ['relatorio' => $arrRelacionamentos]);
    }

    public function atualizaHorarios(){

        $ops = new Operadores;
        $usuarios = $ops->opsArmarios();

        $relacionamento = OperadoresArmarios::select('id_usuario', 'entrada', 'saida')->get();

        foreach ($usuarios as $u){
            foreach($relacionamento as $r){
                if($u['login'] == $r->id_usuario){
                    $r->entrada = $u['entrada'];
                    $r->saida = $u['saida'];
                    $r->save();
                }
            }
        }

    }



    
}
