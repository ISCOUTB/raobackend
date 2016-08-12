<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AlarmsModel extends Model {

    protected $table='alarms';

    protected $fillable = ['failedattendance', 'STUDENTID', 'PERIODO'];
}