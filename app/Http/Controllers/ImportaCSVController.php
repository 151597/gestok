<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Setores;
use App\Models\TipoAtivo;
use App\Models\Ativo;
use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportaCSVController extends Controller
{
    public function telaImportacao(){
        return view('importaCSV');
    }

    public function salvarCSV(Request $request){

        //permite a manipulação de arquivos
        $file = new \SplFileObject($request->file('csvFile'), 'r');
        
        //carrega o csv
        $csv = Reader::createFromFileObject($file);
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        //o objeto construtor de restrição facilita a seleção de registros CSV
        $stmt = Statement::create();

        //consulta os registros
        $registros = $stmt->process($csv);


        $tipo = TipoAtivo::select('id_ativo', 'alfa')->get();
        $setor = Setores::select('id_setor', 'alfa')->get();

        $data = date('Y-m-d H:i:s',time());

        $arrCSV = [];

        $campos = ['gps', 'descricao', 'numero_serie', 'patrimonio'];

        foreach ($registros as $r) {

            $tip = null;
            $set = null;
            $emp = null;
            $situa = null;

            foreach($tipo as $t){
                if($t->alfa == $r['alfa_ativo']){
                    $tip = $t->id_ativo;
                    break;
                }
            }

            foreach($setor as $se){
                if($se->alfa == $r['alfa_setor']){
                    $set = $se->id_setor;
                    break;
                }
            }

            switch ($r['empresa']) {
                case 'CAIXA':
                    $emp = '1';
                    break;
                case 'Gestok':
                    $emp = '2';
                    break;
            }

            switch ($r['situacao']) {
                case 'BOM':
                    $situa = '1';
                    break;
                case 'RUIM':
                    $situa = '2';
                    break;
                case 'ESTRAGADO':
                    $situa = '3';
                    break;
                case 'NAO':
                    $situa = '4';
                    break;
            }

            foreach($campos as $c){
                if(empty($r[$c])){
                    $r[$c] = null;
                }
            }
            
            
            array_push($arrCSV, [
                            'id_ativo' => $tip,
                            'id_estoque' => $emp,
                            'id_setor' => $set,
                            'gps' => $r['gps'],
                            'descricao' => $r['descricao'],
                            'numero_serie' => $r['numero_serie'],
                            'patrimonio' => $r['patrimonio'],
							'ramal'      => $r['ramal'],
                            'situacao' => $situa,
                            'created_at' => $data
                        ]);
            
        }

        $ativo = new Ativo;
        $ativo->insert($arrCSV);
        
        if($ativo){
            return json_encode("Ativos inseridos com sucesso!");
        }


    }

    
}
