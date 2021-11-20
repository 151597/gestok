<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\TipoAtivo;
use Illuminate\Http\Request;

class TipoAtivoController extends Controller
{
    public function cadastroTipoAtivo(){
        return view('cadastroTipoAtivo');
    }

    public function salvarTipoAtivo(Request $request){
        $id_ativo = $request['id_ativo'];
        if(!empty($id_ativo)){
            $save = TipoAtivo::findOrFail($id_ativo);
        }else{
            $save = new TipoAtivo;
        }
        $save->nome = $request['data'];
        $save->alfa = $request['alfa'];
        $save->save();
    }

    public function loadTipoAtivo(){
        $loadTipoAtivo = new TipoAtivo;
        $loadTipoAtivo = DB::table('ativo')
        ->select('id_ativo', 'nome', 'alfa')
        ->whereNull('deleted_at')
        ->orderBy('nome','ASC')
        ->get();

        return json_encode(['data' => $loadTipoAtivo]);
    }

    public function deleteTipoAtivo(Request $request){
        $idCheck = $request->input('data');
        $deleteTipoAtivo = TipoAtivo::whereIn('id_ativo', $idCheck);
        $deleteTipoAtivo->delete();
    }
}
