<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model {

    const ROLE_CLIENT = 1;
    const ROLE_DESIGNER = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'status', 'id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at',
    ];

}
