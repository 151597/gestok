<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class EstoqueProduto extends Model
{
    protected $connection = 'mysql';
    protected $table = 'estoque_produto';
    public $timestamps = false;

}
