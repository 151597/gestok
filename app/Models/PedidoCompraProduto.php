<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PedidoCompraProduto extends Model
{
    protected $connection = 'mysql';
    protected $table = 'pedido_compra_produto';
    //protected $primaryKey = 'id_pedido';
    public $timestamps = false;

}