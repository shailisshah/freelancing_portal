<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class Projects extends Model {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = 2;
    const SUBMITTED = 3;
    const SEND_BACK_TO_CLIENT = 4;
    public static $ProjectSubmissionStatus = [3 => 'Project Mark As Complete', 4 => 'Project Send Back For Changes'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'title', 'description', 'documents', 'due_date', 'assigned_to', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_by', 'updated_by', 'created_dt', 'updated_dt',
    ];
    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function($model) {
            $user = Auth::user();
            $model->created_by = (isset($user->id) && !empty($user->id)) ? $user->id : 0;
            $model->created_dt = Carbon::now();
        });
        static::updating(function($model) {
            $user = Auth::user();
            $model->updated_by = (isset($user->id) && !empty($user->id)) ? $user->id : 0;
            $model->updated_dt = Carbon::now();
        });
    }

}
