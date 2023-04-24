<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class StudioController extends Controller
{
    public function index(Request $request)
    {
        if ($request->search) {
            $data = Studio::where("name", "LIKE", "%" . $request->search . "%")->get();

            return response()->json(["status" => 1, "data" => $data], 200);
        }

        $data = Studio::all()->sortBy("name", SORT_ASC);
        return response()->json(["status" => 1, "data" => $data], 200);
    }

    public function store(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            "name" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        $studio = new Studio;
        $studio->name = $request->name;
        $studio->save();

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $studio], 200);
    }

    public function destroy(Request $request)
    {
        $data = Studio::findOrFail($request->id);
        $data->delete();

        return response()->json(["status" => 1, "message" => "Successfully"], 200);
    }
}
