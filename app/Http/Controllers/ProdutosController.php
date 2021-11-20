<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\TipoAtivo;
use App\Models\Estoque;
use App\Models\Ativo;
use App\Models\Produto;
use App\Models\Fornecedor;
use Illuminate\Http\Request;

class ProdutosController extends Controller{

    public function cadastroProdutos(){
        $loadFornecedores = Fornecedor::select('id_fornecedor', 'razao_social')
        ->orderBy('razao_social')
        ->whereNull('deleted_at')
        ->get();
  
        return view('cadastroProdutosServico',['loadFornecedores' => $loadFornecedores]);
    }

    public function salvarProduto(Request $request){

        $ativo = $request['id'];
        if(!empty($ativo)){
            $save = Produto::findOrFail($ativo);
        }else{
            $save = new Produto;
        }
        $save->descricao = $request['cadastroDescricao'];
        $save->id_fornecedor = $request['cadastroFornecedor'];
        $save->codigo_ean = $request['cadastroEAN'];
        $save->sku = $request['cadastroSKU'];
        $save->tipo = $request['cadastroTipo'];
        $save->valor = $request['cadastroValor'];
     
        $save->save();
    }

    public function loadProduto(){
        
        $loadProduto = DB::table('produto as p')
        ->select('p.id_produto as id_produto', 'p.descricao as descricao', 'f.razao_social as razao', 'p.codigo_ean as ean', 'p.sku as sku', 
        DB::raw("(CASE WHEN p.tipo = 'P' THEN 'Produto' WHEN p.tipo = 'S' THEN 'Serviço' END) as tipo"), 'p.valor as valor', 
        'p.id_fornecedor as id_fornecedor', 'p.tipo as charTipo')
        ->join(DB::raw('gestok.fornecedor as f'),'f.id_fornecedor', '=', 'p.id_fornecedor')
        ->whereNull('p.deleted_at')
        //->whereNull('f.deleted_at')
        ->get();
        return json_encode(['data' => $loadProduto]);
    }

    public function deleteProduto(Request $request){
        $idCheck = $request->input('data');
        $delete = Produto::whereIn('id_produto', $idCheck);
        $delete->delete();
    }
}
