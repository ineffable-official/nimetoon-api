<?php

namespace App\Http\Controllers;

use App\Models\Viewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ViewerController extends Controller
{
    public function index()
    {
        $data = Viewer::all();
        return response()->json(["status" => 1, "data" => $data], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user" => "required|integer",
            "anime" => "required|integer",
            "ip" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        $viewer = new Viewer;
        $viewer->user = $request->user;
        $viewer->anime = $request->anime;
        $viewer->ip = $request->ip;
        $viewer->save();

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $viewer], 200);
    }

    public function destroy(Request $request)
    {
        $data = Viewer::findOrFail($request->id);
        $data->delete();

        return response()->json(["status" => 1, "message" => "Successfully"], 200);
    }
}
