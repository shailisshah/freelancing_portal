<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Punches;

class StatusCron extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For managing the Attendance Reports. (Update the status and work logs)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $data = Punches::where(['status' => Punches::STATUS_ACTIVE])->get();
        foreach ($data as $key => $val) {
            $breakHour = 0;
            if (isset($val->date) && !empty($val->date) && isset($val->break_in) && !empty($val->break_in) && isset($val->break_out) && !empty($val->break_out)) {
                $breakIn = $val->date . ' ' . $val->break_in;
                $breakOut = $val->date . ' ' . $val->break_out;
                $timestampbreakIn = strtotime($breakIn);
                $timestampbreakOut = strtotime($breakOut);
                if ($timestampbreakIn < $timestampbreakOut)
                    $breakHour = abs($timestampbreakIn - $timestampbreakOut) / (60 * 60);
            }
            $TotalHour = 0;
            if (isset($val->date) && !empty($val->date) && isset($val->punch_in) && !empty($val->punch_in) && isset($val->punch_out) && !empty($val->punch_out)) {
                $In = $val->date . ' ' . $val->punch_in;
                $Out = $val->date . ' ' . $val->punch_out;
                $timestampIn = strtotime($In);
                $timestampOut = strtotime($Out);
                if ($timestampIn < $timestampOut)
                    $TotalHour = abs($timestampIn - $timestampOut) / (60 * 60);
            }
            $TotalHour = $TotalHour - $breakHour;
            $work_hrs = floor($TotalHour) . ':' . (($TotalHour * 60) % 60);
            $student_attendance = Punches::where('id', $val->id)->first();
            if ($TotalHour >= 6) {
                $student_attendance->attendance_status = 'P';
            } else {
                $student_attendance->attendance_status = 'A';
            }
            $student_attendance->work_hrs = $work_hrs;
            $student_attendance->update();
            echo 'Attendance status : ' . $student_attendance->attendance_status . ' AND Work Hrs ' . $work_hrs . '\n';
        }
    }

}
