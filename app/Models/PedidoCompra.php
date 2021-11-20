<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PedidoCompra extends Model
{
    protected $connection = 'mysql';
    protected $table = 'pedido_compra';
    protected $primaryKey = 'id_pedido';
    public $timestamps = false;

}