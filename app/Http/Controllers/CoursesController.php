<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\CoursesByTeacherModel;
use App\CoursesByStudentModel;
use App\StudentsByCourseModel;
use App\CoursesModel;
use App\TeachersModel;
use App\StudentsModel;
use App\MatriculasModel;
use App\PeriodosModel;
use Illuminate\Http\Request;
use Request as RequestData;

class CoursesController extends Controller {

    /**
     * FunciÃ³n para mostrar toda la informaciÃ³n de un curso, pasando como referencia el NRC_PERIODO_KEY
     * @param $NRC
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCoursesInfo($NRC) {
        $course = CoursesModel::where("NRC_PERIODO_KEY", "=", $NRC)->first();
        $username = RequestData::input('username');
        if ($course) {
            $role = MatriculasModel::enrolledAs($NRC, $username);
            if (!$role) {
                return response()->json(['No estás matriculado en este curso.']);
            }
            $object = array(
                "subject_name" => $course["NOMBREASIGNATURA"],
                "nrc" => $course["NRC_PERIODO_KEY"],
                "period" => $course["PERIODO"],
                "credits" => $course["CREDITOS"],
                "week_hours" => $course["HORAS_SEMANALES"],
                "subject" => $course["MATERIA"],
                "section" => $course["SECCION"],
                "course" => $course["CURSO"],
                "teacher_id" => $course["DOCENTEID"],
                "role" => $role,
                "links" => array(
                    "statistics_uri" => "/course/" . $course["NRC_PERIODO_KEY"] . "/attendance/",
                    "teacher_uri" => "/teacher/" . $course["DOCENTEID"]
                )
            );
            if ($course["DOCENTEID"] == $username) {
                $students = $this->showStudentsByCourse($NRC, $course);
                $object["students"] = $students;
            } else {
                $object["students"] = [];
            }
        } else {
            $object = "No existe ningún curso con el NRC " . $NRC . ".";
        }
        return response()->json($object);
    }

    public function enrolledAs($NRC, $username) {
        $matricula = MatriculasModel::where("IDNUMBER", "=", $NRC)->where('USERNAME', '=', $username)->first();
        if ($matricula) {
            return $matricula->role;
        } else {
            return false;
        }
    }

    /**
     * Funcion para listar los alumnos de un curso, pasando como referencia el NRC
     * @param $NRC
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showStudentsByCourse($NRC, $course) {
        $studentsbycourse = MatriculasModel::where("IDNUMBER", "=", $NRC)->where('ROLE', '=', 'student')->get();
        $studentByCourseArray = array();
        if (!$studentsbycourse->isEmpty()) {
            foreach ($studentsbycourse as $value) {
                $var = array(
                    "id" => $value->student["ID"],
                    "names" => $value->student["NOMBRES"],
                    "lastnames" => $value->student["APELLIDOS"],
                    "program" => $value->student["PROGRAMA"],
                    "email" => $value->student["EMAIL"],
                    "resource_uri" => "/student/" . $value->student["ID"],
                );
                $studentByCourseArray["students"][] = $var;
            }
        } else {
            if ($course) {
                $studentByCourseArray["students"] = "No hay estudiantes matriculados en el curso con el NRC " . $NRC;
            } else {
                $studentByCourseArray = "El curso con NRC " . $NRC . " no existe";
            }
        }
        return $studentByCourseArray["students"];
    }

    /**
     * Funcion para listar los cursos de un profesor, pasando como referencia el TEACHERID
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCoursesByTeacher($id) {
        $coursesbyteacher = MatriculasModel::where("USERNAME", "=", $id)->where('ROLE', '=', 'editingteacher');
        $coursesbyteacher->where(function($query) {
            $periodos = new PeriodosModel;
            $periodosActivos = $periodos->periodosActivos();
            if (count($periodosActivos) != 0) {
                foreach ($periodosActivos as $periodo) {
                    $query->orWhere('IDNUMBER', 'like', '%' . $periodo->periodo);
                }
            } else {
                $query->orWhere('IDNUMBER', 'like', '%' . "0000-00");
            }
        });

        $coursesbyteacher = $coursesbyteacher->get();
        if (!$coursesbyteacher->isEmpty()) {
            $object = array(
                "id" => $coursesbyteacher[0]->teacher["ID"],
                "names" => $coursesbyteacher[0]->teacher["NOMBRES"],
                "lastnames" => $coursesbyteacher[0]->teacher["APELLIDOS"]
            );
            $object["resource_uri"] = "/teacher/" . $coursesbyteacher[0]["TEACHERID"];

            foreach ($coursesbyteacher as $value) {
                $var = array(
                    "subject_name" => $value->course["NOMBREASIGNATURA"],
                    "nrc" => $value->course["NRC_PERIODO_KEY"],
                    "section" => $value->course["SECCION"],
                    "resource_uri" => "/course/" . $value->course["NRC_PERIODO_KEY"],
                );
                $object["courses"][] = $var;
            }
        } else {
            $teacher = TeachersModel::where("ID", "=", $id)->first();
            if ($teacher) {
                $object = array(
                    "id" => $teacher->ID,
                    "names" => $teacher->NOMBRES,
                    "lastnames" => $teacher->APELLIDOS
                );
                $object["resource_uri"] = "/teacher/" . $teacher->ID;
                $object["courses"] = "El usuario no tiene ningún curso.";
            } else {
                $object = "El usuario no existe o no es un docente.";
            }
        }
        return response()->json($object);
    }

    /**
     * FunciÃ³n para mostrar los cursos de un estudiante, pasando como referencia el STUDENTID
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCoursesByStudent($id) {
        $coursesbystudent = MatriculasModel::where("USERNAME", "=", $id)->where('ROLE', '=', 'student')->where('IDNUMBER', 'not like', 'PREG%');

        $coursesbystudent->where(function($query) {
            $periodos = new PeriodosModel;
            $periodosActivos = $periodos->periodosActivos();
            if (count($periodosActivos) != 0) {
                foreach ($periodosActivos as $periodo) {
                    $query->orWhere('IDNUMBER', 'like', '%' . $periodo->periodo);
                }
            } else {
                $query->orWhere('IDNUMBER', 'like', '%' . "0000-00");
            }
        });

        $coursesbystudent = $coursesbystudent->get();

        if (!$coursesbystudent->isEmpty()) {
            $coursesbystudent_INIT = $coursesbystudent[0];

            $coursesbystudent_JSON = array(
                "student_id" => $coursesbystudent_INIT["USERNAME"],
                "courses" => array()
            );

            foreach ($coursesbystudent as $value) {
                $course = $value->course;
                $var = array(
                    "subject_name" => $course["NOMBREASIGNATURA"],
                    "nrc" => $course["NRC"] . '-' . $course['PERIODO'],
                    "section" => $course["SECCION"],
                    "names" => $course->docente["NOMBRES"],
                    "lastnames" => $course->docente["APELLIDOS"],
                    "teacher_id" => $course["DOCENTEID"],
                );
                $coursesbystudent_JSON["courses"][] = $var;
            }
        } else {
            $student = StudentsModel::where("ID", "=", $id)->first();
            if ($student) {
                $coursesbystudent_JSON = array(
                    "student_id" => $student->ID
                );
                $coursesbystudent_JSON["courses"] = "El usuario no tiene ningún curso.";
            } else {
                $coursesbystudent_JSON = "El estudiante no existe o no está matriculado como estudiante en ningún curso.";
            }
        }

        return response()->json($coursesbystudent_JSON);
    }

}
