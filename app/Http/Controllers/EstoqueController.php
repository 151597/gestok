<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Estoque;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public function cadastroEstoque(){

        return view('cadastroEstoque');
    }

    public function salvarEstoque(Request $request){
        $id_estoque = $request['id_estoque'];
        if(!empty($id_estoque)){
            $save = Estoque::findOrFail($id_estoque);
        }else{
            $save = new Estoque;
        }
        $save->nome = $request['data'];
        $save->save();
    }
    public function loadEstoque(){
        $loadEstoque = new Estoque;
        $loadEstoque = DB::table('estoque')
        ->select('id_estoque', 'nome')
        ->whereNull('deleted_at')
        ->orderBy('nome','ASC')
        ->get();

        return json_encode(['data' => $loadEstoque]);
    }
    public function deleteEstoque(Request $request){
        $idCheck = $request->input('data');
        $deleteEstoque = Estoque::whereIn('id_estoque', $idCheck);
        $deleteEstoque->delete();
    }
}
