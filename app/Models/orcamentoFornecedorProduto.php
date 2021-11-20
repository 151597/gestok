<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class orcamentoFornecedorProduto extends Model
{
    protected $connection = 'mysql';
    protected $table = 'orcamento_fornecedor_produto';
    protected $primaryKey = 'id_orcamento';
    public $timestamps = false;

}