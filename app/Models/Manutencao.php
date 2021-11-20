<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Manutencao extends Model
{
    protected $connection = 'mysql';
    protected $table = 'manutencao';
    protected $primaryKey = 'id_manutencao';
    public $timestamps = false;

}