<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AjusteEstoque extends Model
{
    protected $connection = 'mysql';
    protected $table = 'movimento_estoque_produto';
    protected $primaryKey = 'id_produto';
    public $timestamps = false;

}