<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Operadores extends Model
{
    protected $connection = "pgsql";
    protected $table = 'portal.vw_empregados';   

    public function opsArmarios(){
        return Operadores::select("vw_empregados.login as login", 
        "vw_empregados.nome as nome",
        "e.nome as supervisor", 
        'f.nome as funcao', 
        'vw_empregados.jorn_ent as entrada', 
        'vw_empregados.jorn_sai as saida')
        ->join('portal.vw_funcoes as f', 'portal.vw_empregados.codfuncao', '=', 'f.codfuncao')
        ->join('portal.vw_empregados as e', 'e.login', '=', 'portal.vw_empregados.idsuperv' )
        ->where('vw_empregados.codempresa', 16)
        ->where('vw_empregados.dominio', 'RIODEJANEIRO')
        ->whereNull('vw_empregados.dtdemis')
        ->whereRaw("
        (f.codfuncao = '308' OR
        f.codfuncao = '336' OR
        f.codfuncao = '307' OR
        f.codfuncao = '1022' OR
        f.codfuncao = '89' OR
        f.codfuncao = '1012' OR
        f.codfuncao = '1023'
        )")
        ->orderBy('vw_empregados.nome')
        ->get();
    }

}