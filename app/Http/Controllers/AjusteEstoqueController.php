<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\AjusteEstoque;
use App\Models\EstoqueProduto;
use App\Models\Produto;
use Illuminate\Http\Request;

class AjusteEstoqueController extends Controller
{
    //função que retorna a visão de Ajuste de Estoque    
    public function ajusteEstoque(){

        $loadProduto = new Produto;
        $loadProduto = DB::table('produto')
        ->select('id_produto', 'descricao')
        ->whereNull('deleted_at')
        ->orderBy('descricao','ASC')
        ->get();

        $loadProduto = json_decode($loadProduto, true);

        return view('ajusteEstoque',['loadProduto' => $loadProduto]);
    }
    //função que salva as informações vindo de Request
    public function salvarAjuste(Request $request){

        date_default_timezone_set('America/Sao_Paulo');

        $id = $request['ajusteProduto'];
        $quantidade = $request['ajusteQuantidade'];
        $movimento = $request['ajusteMovimento'];

        $save = new AjusteEstoque;

        $save->id_produto = $id;
        $save->quantidade = $quantidade;
        $save->tipo_movimento = $movimento;
        $save->data_movimento = date('Y-m-d H:i:s',time());
        $save->save();

        
        $estoque = EstoqueProduto::where('id_produto',$id)->first();

        if(is_null($estoque)){
            $estoque = new EstoqueProduto;
            $estoque->id_produto = $id;
            $estoque->quantidade = $quantidade;
            $estoque->save();
        }else{
            if($movimento == 'E'){
                $sum = $estoque->quantidade+$quantidade;
            }elseif($movimento == "S"){
                $sum = $estoque->quantidade-$quantidade;    
            }

            $estoqueS = DB::update("UPDATE gestok.estoque_produto SET quantidade = $sum
                WHERE id_produto = $estoque->id_produto");

        }

        

        
        
    }
    //função que retorna a visão de Relatorio de Estoque
    public function relatorioEstoque(){
        return view('relatoriosEstoque');
    }
    //função que carrega o dataTables da tela de Relatorio de Estoque com historico
    public function loadRelatorioEstoque(){
        $estoque = DB::table('gestok.estoque_produto as e')
        ->select(DB::raw('sum(e.quantidade) as quantidade'), 'p.descricao')
        ->join('gestok.produto as p', 'e.id_produto', '=', 'p.id_produto')
        ->groupBy('e.id_produto', 'p.descricao')
        ->get();
        
        return json_encode(['data' => $estoque]);
    }
}
