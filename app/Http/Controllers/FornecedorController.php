<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Fornecedor;
use App\Models\Pessoas;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function cadastroFornecedor(){

        $loadPessoa = new Pessoas;
        $loadPessoa = DB::table('pessoas')
        ->select('id_pessoa', 'nome')
        ->orderBy('nome','ASC')
        ->get();

        $loadPessoa = json_decode($loadPessoa, true);

        return view('cadastroFornecedor',['loadPessoa'=>$loadPessoa]);
    }

    public function salvarFornecedor(Request $request){
        $fornecedor = $request['id'];
        
        if(!empty($fornecedor)){
            $save = Fornecedor::findOrFail($fornecedor);
        }else{
            $save = new Fornecedor;
        }

        $save->razao_social = $request['cadastroRazao'];
        $save->cpf_cnpj = $request['cadastroCpf_Cnpj'];
        $save->telefone = $request['cadastroTelefone'];
        $save->responsavel = $request['cadastroResponsavel'];
        $save->save();
    }

    public function loadFornecedor(){
        $loadFornecedor = new Fornecedor;
        $loadFornecedor = DB::table('fornecedor')
        ->select('id_fornecedor', 'razao_social', 'cpf_cnpj', 'telefone', 'responsavel')
        ->whereNull('deleted_at')
        ->orderBy('razao_social','ASC')
        ->get();
        return json_encode(['data' => $loadFornecedor]);
    }

    public function deleteFornecedor(Request $request){
        $idCheck = $request->input('data');
        $deleteFornecedor= Fornecedor::whereIn('id_fornecedor', $idCheck);
        $deleteFornecedor->delete();
    }
}
