<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TempPedido extends Model
{
    protected $connection = 'mysql';
    protected $table = 'temp_pedido_compra_produto';
    //protected $primaryKey = 'id_setor';
    public $timestamps = false;

}