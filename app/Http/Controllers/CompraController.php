<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Fornecedor;
use App\Models\Pessoas;
use App\Models\Produto;
use App\Models\PedidoCompra;
use App\Models\PedidoCompraProduto;
use App\Models\orcamentoFornecedorProduto;
use App\Exceptions\CustomException;
use App\Models\orcamentoFornecedor;
use App\Models\orcamento;
use App\Models\TempPedido;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    //função que retorna a visão do Pedido de Compra
    public function pedidoCompra(){

        $loadFornecedor = new Fornecedor;
        $loadFornecedor = DB::table('fornecedor')
        ->select('id_fornecedor', 'razao_social')
        ->orderBy('razao_social','ASC')
        ->whereNull('deleted_at')
        ->get();

        $loadPessoas = new Pessoas;
        $loadPessoas = DB::table('pessoas')
        ->select('id_pessoa', 'nome')
        ->whereRaw("setor = 'Coordenação' OR setor = 'Gerência'")
        ->whereNull('deleted_at')
        ->orderBy('nome','ASC')
        ->get();

        $loadProduto = new Produto;
        $loadProduto = DB::table('produto')
        ->select('id_produto', 'descricao')
        ->whereNull('deleted_at')
        ->orderBy('descricao','ASC')
        ->get();

        $loadFornecedor = json_decode($loadFornecedor, true);
        $loadPessoas = json_decode($loadPessoas, true);
        $loadProduto = json_decode($loadProduto, true);

        $user = Auth::id();
        $deletePedidoTemp = TempPedido::where('id_usuario', $user)->delete();

        return view('pedidoCompra',['loadFornecedor'=>$loadFornecedor, 
        'loadPessoas' => $loadPessoas, 'loadProduto' => $loadProduto]);
    }
    //função que salva os dados do pedido na tela de Pedido de Compra
    public function salvarPedido(Request $request){ 

        $user = Auth::id();

        date_default_timezone_set('America/Sao_Paulo');
        $id = $request['id'];
        $idusuario = $request['idusuario'];

        if($id == 'savePedido'){

            $loadPedido = new TempPedido;
            $loadPedido = DB::table('temp_pedido_compra_produto')
            ->select('id_usuario','id_produto', 'quantidade')
            ->get();
            
            $savePedidoCompra = new PedidoCompra;
            $savePedidoCompra->id_pessoa_solicitante = $request['pedidoSolicitante'];
            $savePedidoCompra->data_pedido = date('Y-m-d H:i:s',time());
            $savePedidoCompra->save();
            $lastid = $savePedidoCompra->id_pedido;

            foreach($loadPedido as $pedido){
                if($pedido->id_usuario == $user){
                    $savePedidoCompraProduto = new PedidoCompraProduto;
                    $savePedidoCompraProduto->id_pedido = $lastid;
                    $savePedidoCompraProduto->id_produto = $pedido->id_produto;
                    $savePedidoCompraProduto->quantidade = $pedido->quantidade;
                    $savePedidoCompraProduto->save();

                    $deletePedidoTemp = TempPedido::where('id_usuario', $user)->delete();

                }
            }
            return json_encode($lastid);
        }

        if($id == 'adicionarPedido'){
           
            $pedidoProduto = $request['pedidoProduto'];
            $pedidoQuantidade = $request['pedidoQuantidade'];

            if(!empty($idusuario)){

                $savePedidoTemp = TempPedido::where('id_usuario',$user)->where('id_produto', $pedidoProduto)->update(['quantidade' => $pedidoQuantidade]);
            }else{
                $savePedidoTemp = new TempPedido;
                $savePedidoTemp->id_usuario = $user;
                $savePedidoTemp->id_produto = $request['pedidoProduto'];
                $savePedidoTemp->quantidade = $request['pedidoQuantidade'];
                $savePedidoTemp->save();
                $lastid = $savePedidoTemp->id;
            }
            
            $loadPedido = new TempPedido;
            $loadPedido = DB::table('temp_pedido_compra_produto')
            ->select(DB::raw('count(id_usuario) as id_usuario', 'count(id_produto) as id_produto'))
            ->where('id_produto', '=', $pedidoProduto)
            //->where('quantidade', '=', $pedidoQuantidade)
            ->get();

            foreach ($loadPedido as $pedido){
                if($pedido->id_usuario > 1){
                    $deletePedidoTemp = TempPedido::where('id', $lastid)->where('id_usuario', $user)
                    ->delete(); 
                   return trigger_error('ERROR PRODUTO');
                }
            }
        }
    }

    //funcao que salva os dados de produto e quantidade do pedido na tela de orcamento
    public function salvarOrcamento(Request $request){ 
        //dd($request);
        $idfornecedor = $request['idfornecedor'];

        $idOrcamento = $request->input('idOrcamento');
        $nPedido = $request->input('nPedido');
        $id_produto = $request->input('produto');
        $quantidade = $request->input('quantidade');
        $valorColumn2 = $request->input('valorColumn2');
        $valorColumn3 = $request->input('valorColumn3');
        $valorColumn4 = $request->input('valorColumn4');
        $valorColumn5 = $request->input('valorColumn5');

        for($j=0;$j<count($idfornecedor);$j++){

            for($i=0;$i<count($quantidade);$i++){

                $save = new orcamentoFornecedorProduto;
                $save->id_orcamento = $idOrcamento;
                $save->id_fornecedor = $idfornecedor[$j];
                $save->id_produto = $id_produto[$i];
                $save->quantidade = $quantidade[$i]; 
                $save->valor_unitario = ${"valorColumn".($j+2)}[$i]; 
                $save->save();

                $savePedidoCompra = new PedidoCompra;
                $savePedidoCompra = PedidoCompra::where('id_pedido', $nPedido)->update(['status' => 'F']);
            } 
        } 
    }

    //funcao que salva o numero do orcamento no modal da tela de orcamento
    public function orcamentoPedido(Request $request){
        date_default_timezone_set('America/Sao_Paulo');

        //array de fornecedores, se já houver um orçamento solicitando este pedido
        //ele trará o que já está cadastrado
        $arrFornecedores = [];
        $lastid = null;

        $id_pedido = $request->input('pedido');

        $orcamento = orcamento::select('id_orcamento')->where('id_pedido', $id_pedido)->first();

        if(is_null($orcamento) || empty($orcamento)){

            $saveOrcamento = new orcamento;
            $saveOrcamento->id_pedido = $id_pedido;
            $saveOrcamento->data_orcamento =  date('Y-m-d H:i:s',time());
            $saveOrcamento->save();
            
            $lastid = $saveOrcamento->id_orcamento;
            
        }else{
            $lastid = $orcamento->id_orcamento;
            $orcaforn = orcamentoFornecedor::select('id_fornecedor')->where('id_orcamento', $orcamento->id_orcamento)->get();

            foreach($orcaforn as $orcs){
                array_push($arrFornecedores, $orcs->id_fornecedor);
            }
        }
        
        return json_encode(['id' => $lastid, 'forns' => $arrFornecedores]);
    }

    //funcao que salva as formas de pagamento na tela de orcamento
    public function salvarPagamento(Request $request){ 

        date_default_timezone_set('America/Sao_Paulo');

        $dataPrimeira = $request['orcamentoPrimeira'];

        $idfornecedor = $request['idfornecedor'];

        $dataPrimeira = date('Y-m-d', strtotime(str_replace('/', '-', $dataPrimeira)));

        $idOrcamento = $request->input('idOrcamento');
         
        for ($i=0;$i<count($idfornecedor);$i++) { 
            $savePagemento =  new orcamentoFornecedor;
            $savePagemento->id_orcamento = $idOrcamento;
            $savePagemento->id_fornecedor = $idfornecedor[$i];
            $savePagemento->id_pedido = $request['idPedido'];
            $savePagemento->forma_pagamento = $request['orcamentoForma'];
            $savePagemento->parcelas = $request['orcamentoParcela'];
            $savePagemento->data_primeira_parcela = $dataPrimeira;
            $savePagemento->banco = $request['orcamentoBanco'];
            $savePagemento->agencia = $request['orcamentoAgencia'];
            $savePagemento->conta = $request['orcamentoConta'];
            $savePagemento->operacao = $request['orcamentoOp'];
            $savePagemento->tipo_conta = $request['orcamentoTipoConta'];
            $savePagemento->save();
        }   
    }
    //funcao que carrega o pedido no dataTables da tela de Pedido de Compra
    public function loadPedido(){

        $user = Auth::id();

        $loadPedido = new TempPedido;
        $loadPedido = DB::table('temp_pedido_compra_produto as a')
        ->select('a.id','a.id_usuario', 'a.id_produto', 'a.quantidade', 'b.descricao')
        ->join('produto as b', 'b.id_produto', '=', 'a.id_produto')
        ->where('id_usuario', $user)
        ->get();
        return json_encode(['data' => $loadPedido]);
    }

    //funcao que carrega o numero de pedido no modal da Tela de orcamento
    public function loadNPedido(){
        $loadNPedido = new PedidoCompra;
        $loadNPedido = DB::table('gestok.pedido_compra as a')
        ->select('a.id_pedido', 'b.nome')
        ->join('gestok.pessoas as b', 'a.id_pessoa_solicitante', '=', 'b.id_pessoa')
        ->where('status', '=', 'A')
        ->get();  

        return json_encode(['data' => $loadNPedido]);
    }

    //funcao que carrega o produto e quantidade do pedido na tela de orcamento
    public function loadOrcamento(Request $request){
        $checkRadio = $request['radio'];
        
        $loadPedido = new PedidoCompraProduto;
        $loadPedido = DB::table('pedido_compra_produto as a')
        ->select(
                'a.id_produto',
                'b.descricao', 
                'a.quantidade')
        ->join('produto as b', 'b.id_produto', '=', 'a.id_produto')
        ->join('pedido_compra as c', 'a.id_pedido', '=', 'c.id_pedido')
        ->where('a.id_pedido', $checkRadio)
        ->get();        
  
        return json_encode(['data' => $loadPedido]);
    }
    
    //funcao que retorna a visao de orcamento
    public function orcamentos(Request $request){

        $loadOrcamento= new PedidoCompra;
        $loadOrcamento = DB::table('fornecedor')
        ->select('id_fornecedor', 'razao_social')
        ->whereNull('deleted_at')
        ->orderBy('razao_social','ASC')
        ->get();

        $loadOrcamento = json_decode($loadOrcamento, true);

        $jsonString = file_get_contents(base_path('public/json/banco_codigo.json'));

        $bancos = json_decode($jsonString, true);

        return view('orcamentosCompra',['loadOrcamento' => $loadOrcamento, 'bancos' => $bancos]);
    }

    /*public function orcamentosCopia(Request $request){

        $loadIdPedido = new PedidoCompra;
        $loadIdPedido = DB::table('pedido_compra as a')
        ->select('id_pedido')
        ->get();

        $loadIdPedido = json_decode($loadIdPedido, true);

        $loadOrcamento= new PedidoCompra;
        $loadOrcamento = DB::table('fornecedor')
        ->select('id_fornecedor', 'razao_social')
        ->get();

        $loadOrcamento = json_decode($loadOrcamento, true);

        return view('orcamentosCompraCopia',['loadIdPedido' => $loadIdPedido, 'loadOrcamento' => $loadOrcamento]);
    }*/

    //funcao que busca a razao social do fornecedor para a coluna no DataTables
    public function loadFornecedorTable(Request $request){
        $selectFornecedor = $request['idfornecedor'];
        $id_orcamento = $request->input('idOrcamento');


        $loadFornecedor = new Fornecedor;
        $loadFornecedor = DB::table('fornecedor as a')
        ->select('b.id_fornecedor', 'a.razao_social')
        ->join('orcamento_fornecedor as b', 'a.id_fornecedor', '=', 'b.id_fornecedor')
        ->whereNotNull('b.forma_pagamento')
        ->whereIn('b.id_fornecedor', $selectFornecedor)
        ->where('b.id_orcamento', $id_orcamento)
        ->whereNull('a.deleted_at')
        ->distinct()
        ->get();

        $loadFornecedor = json_decode($loadFornecedor, true);
        return $loadFornecedor;
    }
    //funcao que retorna a visao da tela de Relatorio de Pedido
    public function relatorioPedido(){
        return view('relatoriosPedido');
    }
    //funcao que carrega os dados no dataTables da tela de Relatorio de Pedido
    public function loadRelatorioPedido(){
        $pedido = DB::table('gestok.pedido_compra as ped')
        ->select('ped.id_pedido as id_pedido', 'pes.nome as nome', DB::raw("date_format(data_pedido,'%d/%m/%Y às %H:%i:%s') as data"),
        DB::raw("(CASE WHEN ped.status = 'A' THEN 'Ativo' WHEN ped.status = 'F' THEN 'Finalizado' END) as status")
        )
        ->join('gestok.pessoas as pes', 'pes.id_pessoa', '=', 'ped.id_pessoa_solicitante')
        ->whereNull('pes.deleted_at')
        ->orderBy('pes.nome','ASC')
        ->get();
        
        return json_encode(['data' => $pedido]);
    }
    //funcao que carrega os dados no modal da tela de Relatorio de Pedido
    public function loadRelatorioPedidoProduto($id){
        
        $produtos = DB::table('gestok.pedido_compra_produto as ped')
        ->select(DB::raw('sum(ped.quantidade) as quantidade'), 'p.descricao as descricao')
        ->join('gestok.produto as p', 'p.id_produto', '=', 'ped.id_produto')
        ->where('id_pedido', $id)
        ->whereNull('p.deleted_at')
        ->groupBy('ped.id_produto', 'p.descricao')
        ->get();
        
        return json_encode($produtos);
    }
    //funcao que retorna a visap da tela de Relatorio de Orcamento
    public function relatorioOrcamento(){
        $loadIdOrcamento = new orcamento;
        $loadIdOrcamento = DB::table('orcamento as o')
        ->select('o.id_orcamento')->join('orcamento_fornecedor as of', 'of.id_orcamento', '=', 'o.id_orcamento')
        ->join('orcamento_fornecedor_produto as ofp', 'ofp.id_orcamento', '=', 'o.id_orcamento')->groupBy('o.id_orcamento')->get();

        $loadIdOrcamento = json_decode($loadIdOrcamento, true);

        return view('relatoriosOrcamento',['loadIdOrcamento' => $loadIdOrcamento]);
    }
    //funcao que carrega os dados no dataTables da tela de Relatorio de Orcamento
    public function laodRelatorioOrcamento(Request $request){
        $idOrcamento = $request->input('id_orcamento');
        $pushArray = [];

        $loadProduto = DB::table('produto as a')
        ->select('a.id_produto', 'a.descricao as produto')
        ->join('orcamento_fornecedor_produto as b', 'a.id_produto', '=', 'b.id_produto')
        ->whereNull('a.deleted_at')
        ->distinct()
        ->get();

        $loadFornecedor = 0;
        $newOrcamento = 0;

        if(!is_null($idOrcamento)){

            $loadFornecedor = DB::table('orcamento_fornecedor_produto as a')
            ->select('a.id_orcamento', 'b.razao_social', 'b.id_fornecedor')
            ->join('fornecedor as b', 'a.id_fornecedor', '=', 'b.id_fornecedor')
            ->where('a.id_orcamento', $idOrcamento)
            ->whereNull('b.deleted_at')
            ->distinct()
            ->get(); 

            //foreach ($loadFornecedor as $key => $value) {
                //dd($loadFornecedor);
                /*$newOrcamento = new orcamentoFornecedorProduto;
                $newOrcamento = DB::table('orcamento_fornecedor_produto as a')
                ->select(
                'a.id_orcamento', 
                'c.descricao', 
                DB::raw("SUM(IF (a.id_fornecedor = $value->id_fornecedor, a.valor_unitario, 0)) AS valor_$key")
                )
                ->join(DB::raw('gestok.fornecedor as b'), 'a.id_fornecedor', '=', 'b.id_fornecedor')
                ->join(DB::raw('gestok.produto as c'), 'c.id_produto', '=', 'a.id_produto')
                ->where('a.id_orcamento', $idOrcamento)
                ->groupBy('a.id_orcamento', 'a.id_produto')
                ->toSql();*/
            $carrie = "";

            foreach ($loadFornecedor as $key => $value) {
                array_push($pushArray, "SUM(IF (a.id_fornecedor = $value->id_fornecedor, a.valor_unitario, 0)) AS valor_".$key);
            }

            $carrie = implode(", ", $pushArray);
            $newOrcamento = DB::table('orcamento_fornecedor_produto as a')
            ->select(
            'a.id_orcamento', 
            'c.descricao',
            DB::raw($carrie) 
            )
            ->join(DB::raw('gestok.produto as c'), 'c.id_produto', '=', 'a.id_produto')
            ->where('a.id_orcamento', $idOrcamento)
            ->whereNull('c.deleted_at')
            ->groupBy('a.id_orcamento', 'a.id_produto')
            ->get();

            //}
            //dd($pushArray);
            /*select id_orcamento, id_produto,
                SUM(IF (id_fornecedor = 4, valor_unitario, 0)) AS ID_4, 
                SUM(IF (id_fornecedor = 5, valor_unitario, 0)) AS ID_5,
                SUM(IF (id_fornecedor = 8, valor_unitario, 0)) AS ID_8
            FROM gestok.orcamento_fornecedor_produto
            GROUP BY id_orcamento, id_produto*/
            
        }

        return json_encode(['data' => $newOrcamento, 'produto' => $loadProduto, 'fornecedor' => $loadFornecedor]);
    }
    
    /***
     * 
     * SELECT 
	 a.id_orcamento, 
	 b.razao_social,
	 c.descricao, 
     a.valor_unitario 
FROM gestok.orcamento_fornecedor_produto as a
INNER JOIN gestok.fornecedor as b
 ON a.id_fornecedor = b.id_fornecedor
INNER JOIN produto as c 
ON c.id_produto = a.id_produto

     * 
     * 
     */
    //funcao que exclui o orcamento de acordo com o Request da tela de Orcamento
    public function deleteOrcamentoFornecedor(Request $request){
        $idOrcamento = $request->input('idOrcamento');
        $idfornecedor = $request->input('idfornecedor');

        $orcamento_forn = orcamentoFornecedor::where('id_fornecedor', $idfornecedor)
        ->where('id_orcamento', $idOrcamento)
        ->delete();
        return $orcamento_forn;
       
    }
    //funcao que exclui o numero do pedido na Tela de Pedido de Compra
    public function deletePedidoProduto(Request $request){
        $id = $request->input('data');
        $deleteTipoAtivo = TempPedido::where('id', $id)->delete();
    }
}
