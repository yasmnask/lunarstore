<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAdmin extends Authenticatable
{
    protected $table = 'user_admins';
    protected $guarded = ['id'];
    public $timestamps = true;
}
