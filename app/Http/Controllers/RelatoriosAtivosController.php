<?php

namespace App\Http\Controllers;

use App\Models\TipoAtivo;
use App\Models\Ativo;
use App\Models\Manutencao;
use App\Models\Estoque;
use App\Models\Setores;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \PDF;

class RelatoriosAtivosController extends Controller
{
    public function relatoriosAtivos(){
        $tipoAtivo = TipoAtivo::select('id_ativo', 'nome')->orderBy('nome')->get();
        $setor = Setores::select('id_setor', 'nome')->orderBy('nome')->get();
        return view('relatoriosAtivos', ['tipoAtivo' => $tipoAtivo, 'setor' => $setor]);
    }

    public function loadRelatoriosAtivos(Request $request){
        $rtn = $request->input("retorno");
        $retorno = date('Y-m-d', strtotime(str_replace('/', '-', $rtn)));

        $s = $request->input('idSetor');
        $t = $request->input('idTipoAtivo');

        $setor = explode(",", $s);
        $tipoAtivo = explode(",", $t);

        $loadRelatorio = DB::table('objeto_ativo as b')
        ->select(
            'b.id_objeto', 
            'b.gps',
            'a.nome', 
            'b.descricao', 
            'b.numero_serie', 
            'd.nome as estoque', 
            'b.situacao', 
            'b.patrimonio', 
            (DB::raw("(select nome from gestok.setor e  where e.id_setor=b.id_setor) as setor")),
            (DB::raw("(select count(id_objeto) from gestok.manutencao m  where m.id_objeto=b.id_objeto) as conserto"))
            )
        ->join('gestok.ativo as a', 'b.id_ativo', '=', 'a.id_ativo')
        ->join('gestok.estoque as d', 'd.id_estoque', '=', 'b.id_estoque')
        ->whereNull('b.deleted_at');

        if($s != 0){
            $loadRelatorio = $loadRelatorio->whereIn('b.id_setor', $setor);
        }

        if($t != 0){
            $loadRelatorio = $loadRelatorio->whereIn('b.id_ativo', $tipoAtivo);
        }

        $loadRelatorio = $loadRelatorio->orderBY(DB::raw('conserto', 'DESC'))
        ->get();

         
        /*if($retorno != '1970-01-01')
            $query->whereBetween('c.data_envio', [$retorno." 00:00:00", $retorno." 23:59:59"]);
        }*/

        return json_encode(['data' => $loadRelatorio]);

    }


    public function guiaManutencao(Request  $request){

        $atvs = $request->input('check');

        $ativos = DB::table('ativo as a')
        ->select('a.nome as nome', 'b.descricao as descricao', 'b.numero_serie as serie', 'b.patrimonio as patrimonio')
        ->join('gestok.objeto_ativo as b', 'b.id_ativo', '=', 'a.id_ativo')
        ->join('gestok.manutencao as c', 'c.id_objeto', '=', 'b.id_objeto')
        ->whereNull('c.data_retorno')
        ->whereNull('b.deleted_at');
        
        if(!is_null($atvs)){
            $ativos = $ativos->whereIn("c.id_manutencao", $atvs);
        }
        
        $ativos = $ativos->get();

       // return view('guiaManutencao');
        return \PDF::loadView('guiaManutencao', ['ativos' => $ativos])
                // Se quiser que fique no formato a4 retrato: ->setPaper('a4', 'landscape')
                //->setPaper('a4', 'landscape')
                ->stream('nome-arquivo-pdf-gerado.pdf');
    }

    public function dashboard(){
        $setor = Setores::select('id_setor', 'nome')->orderBy('nome')->get();
        $tipoAtivo = TipoAtivo::select('id_ativo', 'nome')->orderBy('nome')->get();
        return view('relatoriosDashboard', ['setor' => $setor, 'tipoAtivo' => $tipoAtivo]);
    }

    public function dashTotal(Request $request){
        
        $tipoAtivo = $request->input('tipoAtivo');    
        $setor = $request->input('setor');    


        $ativoTotal = Ativo::select(DB::raw('count(1) as ativo'))->whereNull('deleted_at');
        $manutencaoTotal = DB::table('manutencao as m')
        ->select(DB::raw('count(DISTINCT m.id_objeto) as manutencao'))
        ->join('objeto_ativo as o', 'o.id_objeto', '=', 'm.id_objeto')
        ->whereNull('data_retorno')
        ->whereNull('o.deleted_at');

        $graficoPizza = Ativo::select(DB::raw("count(situacao) as total, 
                            (CASE
                                WHEN situacao = 1 THEN 'BOM'
                                WHEN situacao = 2 THEN 'RUIM'
                                WHEN situacao = 3 THEN 'ESTRAGADO'
                                WHEN situacao = 4 THEN 'NÃO AVALIADO'
                            END) as label"))
                            ->whereNull('deleted_at');


        $graficoBarra = DB::table('objeto_ativo as o')
        ->select(DB::raw("count(o.id_setor) as total,
                (CASE
                    WHEN s.id_setor IS NOT NULL THEN s.nome
                    WHEN s.id_setor IS NULL THEN 'Não Cadastrado'
                END) as label"))
        ->leftJoin('setor as s', 'o.id_setor', '=', 's.id_setor')
        ->whereNull('o.deleted_at')
        ->whereNull('s.deleted_at');



        if(!empty($tipoAtivo)){
            $tp = implode(' or id_ativo = ', $tipoAtivo);
            $oTp = implode(' or o.id_ativo = ', $tipoAtivo);

            $ativoTotal->whereRaw('(id_ativo = '.$tp.")");
            $manutencaoTotal->whereRaw('(o.id_ativo = '.$oTp.")");
            $graficoPizza->whereRaw('(id_ativo = '.$tp.")");
            $graficoBarra->whereRaw('(o.id_ativo = '.$oTp.")");
        }

        if(!empty($setor)){
            $set = implode(' or id_setor = ', $setor);
            $oSet = implode(' or o.id_setor = ', $setor);

            $ativoTotal->whereRaw('(id_setor = '.$set.")");
            $manutencaoTotal->whereRaw('(o.id_setor = '.$oSet.")");
            $graficoPizza->whereRaw('(id_setor = '.$set.")");
            $graficoBarra->whereRaw('(o.id_setor = '.$oSet.")");
        }

        $ativo = $ativoTotal->first();
        $manutencao = $manutencaoTotal->first();
        $pizza = $graficoPizza->groupBy('situacao')->get();
        $barra = $graficoBarra->groupBy('o.id_setor', 's.id_setor', 's.nome')->get();

        return json_encode(['objAtivo' => $ativo, 'objManutencao' => $manutencao, 'pizza' => $pizza, 'barra' => $barra]);
    }

    function relatorioTiposAtivos(){
        $setor = Setores::select('id_setor', 'nome')->orderBy('nome')->get();
        $empresa = Estoque::select('id_estoque', 'nome')->orderBy('nome')->get();
        return view ('relatoriosTipoAtivo', ['setor' => $setor, 'empresa' => $empresa]);
    }

    function loadRelatorioTiposAtivo(Request $request){
        $setor = $request->input('idSetor');
        $empresa = $request->input('idEmpresa');
        $situacao = $request->input('situacao');

        $ativo = DB::table('ativo as a')
        ->select('a.nome', DB::raw("count(o.id_ativo) as quantidade"))
        ->join('objeto_ativo as o', 'o.id_ativo', '=', 'a.id_ativo')
        ->whereNull('o.deleted_at')
        ->groupBy('o.id_ativo', 'a.nome');

        $total = Ativo::whereNull('deleted_at');

        if($setor != -1){
            $ativo = $ativo->where('o.id_setor', $setor);
            $total = $total->where('id_setor', $setor);
        }
        
        if($empresa != -1 && $empresa != 0){
            $ativo = $ativo->where('o.id_estoque', $empresa);
            $total = $total->where('id_estoque', $empresa);
        }elseif($empresa == 0){
            $ativo = $ativo->where('o.id_estoque', '!=', 1)->where('o.id_estoque', '!=', 2);
            $total = $total->where('id_estoque', '!=', 1)->where('id_estoque', '!=', 2);
        }
        
        if($situacao != -1){
            $ativo = $ativo->where('o.situacao', $situacao);
            $total = $total->where('situacao', $situacao);
        }
        

        $ativo = $ativo->orderBy('a.nome')->get();

        

        return json_encode(['data' => $ativo, 'total' => $total->count()]);
    }


}