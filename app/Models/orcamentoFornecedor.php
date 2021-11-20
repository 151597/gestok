<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class orcamentoFornecedor extends Model
{
    protected $connection = 'mysql';
    protected $table = 'orcamento_fornecedor';
    protected $primaryKey = 'id_orcamento';
    public $timestamps = false;

}