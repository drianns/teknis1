<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsLevelUser extends Model
{
    protected $table = 'msleveluser';
    protected $primaryKey = 'LevelUserID';
    public $incrementing = false; // Karena ID-nya kita input manual
    public $timestamps = false;
}
