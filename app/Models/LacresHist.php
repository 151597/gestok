<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LacresHist extends Model
{

    protected $connection = 'mysql';
    protected $table = 'lacres_hist';
    protected $primaryKey = 'ID';
    public $timestamps = false;


}