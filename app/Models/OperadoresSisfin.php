<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class OperadoresSisfin extends Model
{
    protected $connection = "workforce";
    protected $table = 'sisfin.vw_funcionario';   

}