<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'produto';
    protected $primaryKey = 'id_produto';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

}