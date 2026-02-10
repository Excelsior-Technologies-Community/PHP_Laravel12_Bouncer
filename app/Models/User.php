<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use HasRolesAndAbilities;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
