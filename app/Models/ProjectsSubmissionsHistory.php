<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class ProjectsSubmissionsHistory extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'projects_id', 'messages', 'documants','created_by', 'created_dt',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public $table = 'projects_submissions_history';
    public $timestamps = false;
}
