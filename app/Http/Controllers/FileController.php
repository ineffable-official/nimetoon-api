<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function index()
    {
        $data = File::all();
        return response()->json(["status" => 1, "data" => $data], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "size" => "required|integer",
            "mime" => "required|string",
            "url" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        $File = new File;
        $File->name = $request->name;
        $File->size = $request->size;
        $File->mime = $request->name;
        $File->url = $request->url;
        $File->save();

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $File], 200);
    }

    public function destroy(Request $request)
    {
        $data = File::findOrFail($request->id);
        $data->delete();

        return response()->json(["status" => 1, "message" => "Successfully"], 200);
    }
}
