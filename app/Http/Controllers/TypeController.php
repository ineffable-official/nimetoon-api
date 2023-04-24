<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->search) {
            $data = Type::where("name", "LIKE", "%" . $request->search . "%")->get();

            return response()->json(["status" => 1, "data" => $data], 200);
        }

        $data = Type::all()->sortBy("name", SORT_ASC);
        return response()->json(["status" => 1, "data" => $data], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        $type = new Type;
        $type->name = $request->name;
        $type->save();

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $type], 200);
    }

    public function destroy(Request $request)
    {
        $data = Type::findOrFail($request->id)->delete();

        return response()->json(["status" => 1, "message" => "Successfully"], 200);
    }
}
