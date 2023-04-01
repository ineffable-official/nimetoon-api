<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AnimeController extends Controller
{
    public function index()
    {
        $data = Anime::all();
        return response()->json(["status" => 1, "data" => $data], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|string",
            "slug" => "required|string",
            "type" => "required|integer",
            "episodes" => "required|integer",
            "status" => "required|integer",
            "aired_from" => "required|date",
            "aired_to" => "required|date",
            "season" => "required|integer",
            "studio" => "required|integer",
            "genres" => "required|string",
            "images" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        $images_file = $request->file("images");
        $images = null;
        if ($images_file) {
            $images = Storage::put("/images", $images_file);

            $file = new File();
            $file->name = $images_file->hashName();
            $file->size = $images_file->getSize();
            $file->mime = $images_file->getExtension();
            $file->url = $images;
        }

        $anime = new Anime;
        $anime->title = $request->title;
        $anime->slug = $request->slug;
        $anime->type = $request->type;
        $anime->episodes = $request->episodes;
        $anime->status = $request->status;
        $anime->aired_from = $request->aired_from;
        $anime->aired_to = $request->aired_to;
        $anime->season = $request->season;
        $anime->studio = $request->studio;
        $anime->genres = $request->genres;
        $anime->images = $images;
        $anime->save();

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $anime], 200);
    }

    public function destroy(Request $request)
    {
        $data = Anime::findOrFail($request->id);
        $data->delete();

        return response()->json(["status" => 1, "message" => "Successfully"], 200);
    }
}
