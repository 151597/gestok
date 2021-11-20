<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Armario extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'armarios';
    protected $primaryKey = 'numeracao';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

}