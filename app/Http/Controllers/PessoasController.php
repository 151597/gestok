<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pessoas;
use Illuminate\Http\Request;

class PessoasController extends Controller
{
    public function cadastroPessoas(){
        return view('cadastroPessoas');
    }

    public function salvarPessoas(Request $request){
        $id_pessoa = $request['id_pessoa'];
        if(!empty($id_pessoa)){
            $save = Pessoas::findOrFail($id_pessoa);
        }else{
            $save = new Pessoas;
        }
        $save->matricula = $request['matricula'];
        $save->nome = $request['nome'];
        $save->setor = $request['setor'];
        $save->save();
    }

    public function loadPessoas(){
        $loadPessoa = new Pessoas;
        $loadPessoa = DB::table('pessoas')
        ->select('id_pessoa', 'nome', 'matricula', 'setor')
        ->whereNull('deleted_at')
        ->orderBy('nome','ASC')
        ->get();
        return json_encode(['data' => $loadPessoa]);
    }

    public function deletePessoas(Request $request){
        $idCheck = $request->input('data');
        $deletePessoas = Pessoas::whereIn('id_pessoa', $idCheck);
        $deletePessoas->delete();
    }
}
