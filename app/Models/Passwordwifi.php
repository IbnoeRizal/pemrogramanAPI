<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passwordwifi extends Model
{
    protected $table = 'passwordwifi';
    protected $guarded = [
        'nim',
        'birth_date'
    ];
}
