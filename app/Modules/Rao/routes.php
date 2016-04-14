<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 13/04/16
 * Time: 03:03 PM
 */

use App\Modules\rao\Controllers\StatisticsController;
use App\Modules\rao\Controllers\AlarmsController;

//Route::group(['middleware' => 'tokenauth'], function() {

Route::group(array('namespace' => 'App\Modules\Rao\Controllers'), function() {
    Route::resource('attendance', 'AttendanceController');
    Route::group(['middleware' => 'filter'], function () {
        /*
         * Muestra las estadisticas de asistencia de un estudiante a un curso en %
         */
        Route::get('student/{id}/course/{NRC}/attendance', 'StatisticsController@showStatisticsByStudentByCourse');

        /*
         * Muestra las estadisticas de asistencia de un curso
         */
        Route::get('course/{NRC}/attendance', function ($NRC) {
            Event::fire('course.showCoursesInfo', $NRC);
            $controller = new StatisticsController();
            return $controller->showStatisticsByCourse($NRC);
        });

        /*
         * Muestra las estadisticas de asistencia de un estudiante
         */
        //Route::get('student/{id}/attendance', 'StatisticsController@showStatisticsByStudent');

        /*
         * Muestra las alarmas por falta de asistencia de estudiantes a un curso
         */
        Route::get('course/{NRC}/alarms', function ($NRC) {
            //Event::fire('course.showCoursesInfo', $NRC);
            $controller = new AlarmsController();
            return $controller->showCoursesAlarms($NRC);
        });

        /*
        * Muestra las alarmas por falta de asistencia de estudiantes a un curso
        */
        /*Route::get('course/{NRC}/student/{id}/alarms', function ($NRC, $id) {
            //Event::fire('course.showCoursesInfo', $NRC);
            $controller = new AlarmsController();
            return $controller->showAlarmsByStudentByCourse($NRC);
        });*/
    });
});

//});