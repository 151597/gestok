<?php

namespace App\Http\Controllers;

use App\Models\AjusteEstoque;
use App\Models\EstoqueProduto;
use App\Models\NotaFiscal;
use App\Models\TempEstoqueProduto;
use App\Models\Produto;
use App\Models\Fornecedor;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use NFePHP\DA\NFe\Danfe;

class ImportacaoController extends Controller
{
    public function importacaoNota(){

        $produtos = Produto::select('id_produto', 'descricao')->get();
        return view('importacaoNota', ['produtos' => $produtos]);
    }

    public function salvarXML(Request $request){

        $xml = $request->file('xmlFile');
        $sxml1 = simplexml_load_file($xml);

        $sxml2 = json_encode($sxml1);
        $sxml = json_decode($sxml2);

        $idNFE = $sxml->NFe->infNFe->{'@attributes'}->Id;

        $verificaNota = NotaFiscal::where('idNFE', $idNFE)->first();

        if(is_null($verificaNota)){

            if(!is_null($sxml->NFe->infNFe->emit->xFant)){
                $nome = $sxml->NFe->infNFe->emit->xFant;
            }else{
                $nome = $sxml->NFe->infNFe->emit->xNome;
            }

            $fornecedor = Fornecedor::select('id_fornecedor')->whereRaw("(razao_social = '".$nome."'
             OR cpf_cnpj = '".$sxml->NFe->infNFe->emit->CNPJ."')")
            ->first(); 

            $f = $fornecedor;

            if(is_null($fornecedor)){
                $forn = new Fornecedor();
                $forn->razao_social = $nome;
                $forn->cpf_cnpj = $sxml->NFe->infNFe->emit->CNPJ;
                $forn->telefone = $sxml->NFe->infNFe->emit->enderEmit->fone;
                $forn->responsavel = 0;
                $forn->save();

                $f = $forn;
            }

            $nota = new NotaFiscal();

            $nota->cpf_cnpj_fornecedor = $sxml->NFe->infNFe->emit->CNPJ;
            $nota->fantasia_fornecedor = $nome;
            $nota->xml_nfe = file_get_contents($xml);
            $nota->valor_total = $sxml->NFe->infNFe->total->ICMSTot->vNF;
            $nota->idNFE = $idNFE;
            $nota->id_fornecedor = $f->id_fornecedor;
            $nota->save();

            for($i=0;$i<count($sxml1->NFe->infNFe->det);$i++){
                $temp = new TempEstoqueProduto();
                $temp->id_nota = $nota->id_nota_fiscal;
                $temp->produto = $sxml->NFe->infNFe->det[$i]->prod->xProd;
                $temp->quantidade = $sxml->NFe->infNFe->det[$i]->prod->qCom;
                $temp->unidade_n = $sxml->NFe->infNFe->det[$i]->prod->uCom;
                $temp->save();
            }   

            return json_encode('SALVO COM SUCESSO!');
        }else{
            return json_encode('ERRO! ESSA NOTA JÁ FOI CADASTRADA NO SISTEMA!');
        }
        
        
    }

    public function salvarRelacionamento(Request $request){
        $id = $request->input('id_produto_nota');

        $temp = TempEstoqueProduto::find($id);

        $temp->id_produto_sistema = $request->input('produtoSistema');
        $temp->quantidade_sistema = $request->input('qtdeSistema');
        $temp->save();
        
    }

    public function loadTemp(){
        
        $temp = DB::table('gestok.temp_estoque_produto as e')
        ->select('e.id_temp_estoque_produto as id_t', 'e.id_nota', 'e.produto', 
        'e.quantidade', 'e.unidade_n', 'e.id_produto_sistema', 'p.descricao', 'e.quantidade_sistema')
        ->leftJoin('gestok.produto as p', 'e.id_produto_sistema', '=', 'p.id_produto')
        ->get();
        
        return json_encode(['data' => $temp]);
    }

    public function update(){
        date_default_timezone_set('America/Sao_Paulo');
        
        $temp = TempEstoqueProduto::select('id_temp_estoque_produto', 'id_produto_sistema', 'quantidade_sistema')
        ->whereNotNull('id_produto_sistema')
        ->whereNotNull('quantidade_sistema')
        ->get();

        foreach($temp as $t){
            

            $ajuste = new AjusteEstoque;

            $ajuste->id_produto = $t->id_produto_sistema;
            $ajuste->quantidade = $t->quantidade_sistema;
            $ajuste->tipo_movimento = 'E';
            $ajuste->data_movimento = date('Y-m-d H:i:s',time());
           

            $estoque = EstoqueProduto::where('id_produto',$t->id_produto_sistema)->first();

            if(is_null($estoque)){
                $estoque = new EstoqueProduto;
                $estoque->id_produto = $t->id_produto_sistema;
                $estoque->quantidade = $t->quantidade_sistema;
                $estoque->save();
            }else{
                $sum = $estoque->quantidade+$t->quantidade_sistema;
                $estoqueS = DB::update("UPDATE gestok.estoque_produto SET quantidade = $sum
                WHERE id_produto = $estoque->id_produto");
            }

            
            $ajuste->save();

            $t->delete();
        }
    }

    public function relatorioNotas(){
        $fornecedor = Fornecedor::select('id_fornecedor', 'razao_social')->get();
        return view ('relatorioNotas', ['fornecedor' => $fornecedor]);
    }

    public function loadRelatorioNotas(Request $request){

        $id = $request->input('id_fornecedor');

        $notas = NotaFiscal::select('id_nota_fiscal', 'cpf_cnpj_fornecedor', 'fantasia_fornecedor', 'valor_total', 'idNFE');

        if(!is_null($id)){
            $notas = $notas->where('id_fornecedor', $id);
        }

        $notas = $notas->get();
        
        return json_encode(['data' => $notas]);
    }


    public function loadNotaXML($id){
        $nota = NotaFiscal::select('xml_nfe')->find($id);

        $xml = $nota->xml_nfe;

        $danfe = new Danfe($xml);
        $danfe->debugMode(false);

        $pdf = $danfe->render();
        header('Content-Type: application/pdf');
        
        echo $pdf;

    }

}