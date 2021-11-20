<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoas extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'pessoas';
    protected $primaryKey = 'id_pessoa';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

}