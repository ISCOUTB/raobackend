<?php
/**
 * Created by PhpStorm.
 * User: erick
 * Date: 13/04/16
 * Time: 03:03 PM
 */

Route::group(array('namespace' => 'App\Modules\Rao\Controllers'), function() {
    Route::resource('attendance', 'AttendanceController');
});
