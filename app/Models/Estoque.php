<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estoque extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'estoque';
    protected $primaryKey = 'id_estoque';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

}
