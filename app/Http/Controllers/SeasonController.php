<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeasonController extends Controller
{
    public function index(Request $request)
    {
        if ($request->search) {
            $data = Season::where("name", "LIKE", "%" . $request->search . "%")->get();

            return response()->json(["status" => 1, "data" => $data], 200);
        }

        $data = Season::all()->sortBy("name", SORT_ASC);
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

        $season = new Season;
        $season->name = $request->name;
        $season->save();

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $season], 200);
    }

    public function destroy(Request $request)
    {
        $data = Season::findOrFail($request->id);
        $data->delete();

        return response()->json(["status" => 1, "message" => "Successfully"], 200);
    }
}
