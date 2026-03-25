<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsUser extends Model
{
    protected $table = 'msuser';

    protected $primaryKey = 'USERID';
    public $incrementing = false; // Karena USERID di SQL bukan auto-increment
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'USERID',
        'USERNAME',
        'NAME',
        'PASSWORD',
        'LEVELUSER',
        'EMAIL_ADDRESS',
        'ORGANIZATION_NAME',
        'Status',
        'NA',
        'DATECREATE',
        'Description',
    ];
}
