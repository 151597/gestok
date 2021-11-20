<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Lacres;
use App\Models\LacresHist;
use App\Models\OperadoresSisfin;
use Illuminate\Http\Request;
use DateTime;
use Auth;


class RelacionamentoAtivosController extends Controller
{
    public function relacaoAtivos(){

        return view("relacionamentoAtivos");
    }

    public function usuariosCPAtivos(){
        
        $ops = new OperadoresSisfin;
        $operadores = $ops->select('cdmatrfuncionario as login_dac', 'nmfuncionario as nome')
        ->where('desitcfunc', 'TRABALHANDO')->orderBy('nmfuncionario')->get();

        return json_encode(['data' => $operadores]);
    }

    public function salvarRelacionamentoAtivo(Request $request){
        
        $retorno = null;

        $gestok = $request->input('gestok');
        $nome = $request->input('nome');
        $num_lacre = $request->input('num_lacre');
        $descricao = $request->input('descricao');
        $id = $request->input('check_id');

        $lacre = Lacres::find($id);

        if(is_null($lacre)){
            $lacre = new Lacres;
        }
        
        $lacre->ID_GESTOK = $gestok;
        $lacre->NOME = $nome;
        $lacre->NUM_LACRE = $num_lacre;
        $lacre->save();

        if($lacre){
            $hist = new LacresHist;
            $hist->ID_GESTOK = $gestok;
            $hist->NOME = $nome;
            $hist->NUM_LACRE = $num_lacre;
            $hist->DATA_ALTERACAO = date('Y-m-d H:i:s',time());
            $hist->ID_RESPONSAVEL = Auth::id();
            $hist->DESCRICAO = $descricao;
            $hist->ID_LACRES = $lacre->ID;
            $hist->save();
            $retorno = "Salvo com sucesso!";
        }else{
            $retorno = "Erro!";
        }

        return json_encode($retorno);
    }

    public function loadOpsLacres(){
        $relacionamento = Lacres::select('ID', 'ID_GESTOK', 'NOME', 'NUM_LACRE')->get();

        return json_encode(['data' => $relacionamento]);
    }

    public function loadOpsLacresHist($id){

        $relacionamento = LacresHist::select('ID_GESTOK', 'NOME', 'NUM_LACRE', 'DESCRICAO',
         DB::raw("date_format(DATA_ALTERACAO,'%d/%m/%Y às %H:%i:%s') as data"), 'u.name as usuario')
        ->join('users as u', 'u.id', '=', 'lacres_hist.ID_RESPONSAVEL')
        ->where('ID_LACRES', $id)
        ->get();

        return json_encode(['data' => $relacionamento]);
    }



    public function deleteRelacionamentoLacre(Request $request){
        $idCheck = $request->input('data');
        $lacre = Lacres::whereIn('ID', $idCheck);
        $lacre->delete();
    }

    

    
}
