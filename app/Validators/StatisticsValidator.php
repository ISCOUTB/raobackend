<?php

namespace App\Validators;

use App\AttendanceModel;
use App\MatriculasModel;
use App\StudentsModel;
use App\CoursesModel;
use Request as RequestData;

class StatisticsValidator {

    public $validationPass;
    public $validationMessage;

    public function validateUserEnrol($username, $id, $course) {
        $NRC = $course->NRC_PERIODO_KEY;
        $matriculado = MatriculasModel::enrolledAs($NRC, $id);
        
        $sameUser = $id != $username;
        if ($sameUser) {
            if (!$course) {
                $this->validationMessage = "No existe un curso con el NRC " . $NRC;
                $this->validationPass = false;
                return false;
            }
            if (strtolower($course->DOCENTEID) != $username || $matriculado == null) {
                $this->validationMessage = "No tienes acceso a esta información.";
                $this->validationPass = false;
                return false;
            }
        } else {
            if ($matriculado == null) {
                $this->validationMessage = "No estas matriculado en este curso";
                $this->validationPass = false;
                return false;
            }
        }

        $data = AttendanceModel::where("STUDENTID", "=", $id)->where("NRC", "=", $NRC)->get();
        if ($data->isEmpty()) {
            $student = StudentsModel::where("ID", "=", $id)->first();
            if (!$student) {
                $this->validationMessage = "No existe un estudiante con el código " . $id;
                $this->validationPass = false;
                return false;
            }
        }

        $this->validationPass = true;
        return $data;
    }

    public function validateIsTeacherOfCourse($username, $NRC) {
        $course = CoursesModel::where("NRC_PERIODO_KEY", "=", $NRC)->first();
        if (strtolower($course->DOCENTEID) != $username) {
            $this->validationMessage = "No tienes acceso a esta información.";
            $this->validationPass = false;
            return false;
        } else {
            $this->validationPass = true;
            return true;
        }
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

