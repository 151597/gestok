<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TempEstoqueProduto extends Model
{
    protected $connection = 'mysql';
    protected $table = 'temp_estoque_produto';
    protected $primaryKey = 'id_temp_estoque_produto';
    public $timestamps = false;

}