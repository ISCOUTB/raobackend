<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\CoursesByTeacherModel;
use App\CoursesByStudentModel;
use App\StudentsByCourseModel;
use Illuminate\Http\Request;

class CoursesController extends Controller {

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	/*
	 * Funcion para listar los cursos de un profesor con su codigo
	 * */
    public function showCoursesByTeacher($id)
	{
        $data = CoursesByTeacherModel::where("TEACHERID", "=", $id)->get();

        return response()->json($data);
	}

    /*
     * Funcion para listar los alumnos de un curso, pasando como referencia el NRC_PERIODO_KEY
     * */
    public function showStudentsByCourse($NRC)
    {
        $data = StudentsByCourseModel::where("NRC", "=", $NRC)->get();

        return response()->json($data);
    }

    public function showCoursesByStudent($id)
    {
        $data = CoursesByStudentModel::where("STUDENTID", "=", $id)->get();

        return response()->json($data);
    }

}
