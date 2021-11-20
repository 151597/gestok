<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'fornecedor';
    protected $primaryKey = 'id_fornecedor';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

}