<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        if ($request->search) {
            $data = Genre::where("name", "LIKE", "%" . $request->search . "%")->get();

            return response()->json(["status" => 1, "data" => $data], 200);
        }

        $data = Genre::all();
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

        $genre = new Genre;
        $genre->name = $request->name;
        $genre->save();

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $genre], 200);
    }

    public function destroy(Request $request)
    {
        $data = Genre::findOrFail($request->id);
        $data->delete();

        return response()->json(["status" => 1, "message" => "Successfully"], 200);
    }
}
