<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MovimentoEstoque extends Model
{
    protected $connection = 'mysql';
    protected $table = 'movimento_estoque';
    protected $primaryKey = 'id_movimento';
    public $timestamps = false;

}