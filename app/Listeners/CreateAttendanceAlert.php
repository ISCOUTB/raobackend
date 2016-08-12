<?php

namespace App\Listeners;

use App\Events\AttendanceTaken;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\AttendanceModel;
use App\AlarmsModel;

class CreateAttendanceAlert {

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AttendanceTaken  $event
     * @return void
     */
    public function handle(AttendanceTaken $event, $data = 0) {
        $periodo = explode('-', $event->attendance[0]->NRC)[1];
        foreach ($event->attendance as $attendance) {
            if ($attendance->ATTENDANCE == 0) {
                $inasistencias = AttendanceModel::where('STUDENTID', '=', $attendance->STUDENTID)
                                ->where('ATTENDANCE', '=', 0)->count();
                if ($inasistencias >= 3) {
                    $alerts = AlarmsModel::where('STUDENTID', '=', $attendance->STUDENTID)->first();
                    if ($alerts) {
                        $alerts->failedattendance = $inasistencias;
                        $alerts->save();
                    } else {
                        $alerts = new AlarmsModel();
                        $alerts->STUDENTID = $attendance->STUDENTID;
                        $alerts->failedattendance = $inasistencias;
                        $alerts->PERIODO = $periodo;
                        $alerts->save();
                    }
                }
            }
        }
    }

}
