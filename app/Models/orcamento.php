<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class orcamento extends Model
{
    protected $connection = 'mysql';
    protected $table = 'orcamento';
    protected $primaryKey = 'id_orcamento';
    public $timestamps = false;

}