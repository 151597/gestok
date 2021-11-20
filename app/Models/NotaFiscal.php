<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class NotaFiscal extends Model
{
    protected $connection = 'mysql';
    protected $table = 'nota_fiscal';
    protected $primaryKey = 'id_nota_fiscal';
    public $timestamps = false;

}