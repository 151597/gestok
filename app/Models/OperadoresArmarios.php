<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class OperadoresArmarios extends Model
{
    protected $connection = 'mysql';
    protected $table = 'operadores_armarios';
    protected $primaryKey = 'id_ops_armarios';
    public $timestamps = false;

}