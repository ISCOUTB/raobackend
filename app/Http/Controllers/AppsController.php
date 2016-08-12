<?php

namespace App\Http\Controllers;

use App\PeriodosModel;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Apps;

class AppsController extends Controller {

    public function index() {
        $apps = Apps::all();
        if ($apps->isEmpty()) {
            $apps = null;
        }
        return view('apps.index', compact('apps'));
    }

    public function create() {
        return view('apps.create');
    }

    public function store(Request $request) {
        $name = $request->input('name');
        $app = Apps::where("name", "=", $name)->first();
        if (!$app) {
            $newApp = new Apps();
            $newApp->name = $name;
            $newApp->hash = self::generateHash(24);
            $newApp->save();
            return redirect('/admin/apps');
        } else {
            return redirect('/admin/apps/create');
        }
    }

    public function edit($id) {
        $app = Apps::find($id);
        if ($app) {
            return view('apps.edit', compact('app'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request) {
        $app = Apps::find($id);
        if ($app) {
            $app->name = $request->name;
            $app->save();
            return redirect('/admin/apps');
        }
    }

    public function destroy($id, Request $request) {
        $app = Apps::find($id)->delete();
        return redirect('admin/apps');
    }

    public function refreshHash($id) {
        $app = Apps::find($id);
        $app->hash = self::generateHash(24);
        $app->save();
        
        return redirect('/admin/apps');
    }

    function generateHash($len) {
        //String
        $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        //String length
        $string_length = strlen($string);
        //hash var
        $hash = "";
        //Define hash length
        $hash_length = $len;
        for ($i = 1; $i <= $hash_length; $i++) {
            //Random number between 0 and the string_length-1
            $pos = rand(0, $string_length - 1);

            //In each iteration, add a char correspondent to $pos position in the $string to the hash string randomly
            $hash .= substr($string, $pos, 1);
        }
        $app = Apps::where('hash', '=', $hash)->first();
        if ($app) {
            self::generateHash(24);
        } else {
            return $hash;
        }
    }

}
