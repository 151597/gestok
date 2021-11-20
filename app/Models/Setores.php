<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setores extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'setor';
    protected $primaryKey = 'id_setor';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

}