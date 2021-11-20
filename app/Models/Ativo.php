<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ativo extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'objeto_ativo';
    protected $primaryKey = 'id_objeto';
    //public $timestamps = false;

    protected $dates = ['deleted_at'];

}