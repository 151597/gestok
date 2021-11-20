<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Setores;
use Illuminate\Http\Request;

class SetoresController extends Controller
{
    public function cadastroSetores(){
        return view('cadastroSetores');
    }

    public function salvarSetores(Request $request){
        $id_setor = $request['id_setor'];
        if(!empty($id_setor)){
            $save = Setores::findOrFail($id_setor);
        }else{
            $save = new Setores;
        }
        $save->nome = $request['data'];
        $save->alfa = $request['alfa'];
        $save->save();
    }

    public function loadSetores(){
        $loadSetores = new Setores;
        $loadSetores = DB::table('setor')
        ->select('id_setor', 'nome', 'alfa')
        ->whereNull('deleted_at')
        ->orderBy('nome','ASC')
        ->get();

        return json_encode(['data' => $loadSetores]);
    }

    public function deleteSetores(Request $request){
        $idCheck = $request->input('data');
        $deleteSetores = Setores::whereIn('id_setor', $idCheck);
        $deleteSetores->delete();
    }
}
