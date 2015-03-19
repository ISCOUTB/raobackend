<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\StudentsModel;
use App\TeachersModel;
use Illuminate\Http\Request;

class PersonsController extends Controller {

    /**
     * Función para mostrar toda la información de un estudiante, pasando como referencia el ID
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showStudentsInfo($id)
    {
        $data = StudentsModel::where("ID", "=", $id)->get();

        return response()->json($data);
    }

    /**
     * Función para mostrar toda la información de un profesor, pasando como referencia el ID
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTeachersInfo($id)
    {
        $data = TeachersModel::where("ID", "=", $id)->get();

        return response()->json($data);
    }
}
