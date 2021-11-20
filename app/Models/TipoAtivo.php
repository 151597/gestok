<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAtivo extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'ativo';
    protected $primaryKey = 'id_ativo';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

}