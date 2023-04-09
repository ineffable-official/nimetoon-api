<?php

namespace App\Http\Controllers;

use App\Models\Viewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ViewerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->search) {
            $data = Viewer::where("name", "LIKE", "%" . $request->search . "%")->get();

            return response()->json(["status" => 1, "data" => $data], 200);
        }

        $data = Viewer::all();
        return response()->json(["status" => 1, "data" => $data], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user" => "required|integer",
            "video" => "required|integer"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        $viewer = new Viewer;
        $viewer->user = $request->user;
        $viewer->video = $request->video;
        $viewer->ip = $request->ip();
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
