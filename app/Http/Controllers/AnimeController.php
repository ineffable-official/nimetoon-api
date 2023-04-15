<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\File;
use App\Models\Genre;
use App\Models\Season;
use App\Models\Status;
use App\Models\Studio;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AnimeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->search) {
            $data = Anime::where("title", "LIKE", "%" . $request->search . "%")->get();

            return response()->json(["status" => 1, "data" => $this->formatData($data)], 200);
        }

        if ($request->slug) {
            $data = Anime::where("slug", $request->slug)->get();

            return response()->json(["status" => 1, "data" => $this->formatData($data)], 200);
        }

        $data = Anime::all();

        $data_f = $this->formatData($data);

        return response()->json(["status" => 1, "data" => $data_f], 200);
    }

    function formatData($data)
    {
        foreach ($data as $key => $value) {
            $data[$key]->type = Type::find($value->type);
            $data[$key]->status = Status::find($value->status);
            $data[$key]->studio = Studio::find($value->studio);
            $data[$key]->season = Season::find($value->season);
            $genres_decod = json_decode($value->genres);
            $genre_list = array();
            foreach ($genres_decod as $genre) {
                $genre_list[] = Genre::find($genre);
            }
            $data[$key]->genres = $genre_list;
        }

        return $data;
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
            "descriptions" => "required|string",
            "images" => "required|file",
            "images_square" => "required|file"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        $images_file = $request->file("images");
        $images = null;
        if ($images_file) {
            $images = Storage::disk("public")->put("/images", $images_file);

            $file = new File();
            $file->name = $images_file->hashName();
            $file->size = $images_file->getSize();
            $file->mime = $images_file->getExtension();
            $file->url = $images;
            $file->save();
        }

        $images_square_file = $request->file("images_square");
        $images_square = null;

        if ($images_square_file) {
            $images_square = Storage::disk("public")->put("/images", $images_square_file);

            $file = new File();
            $file->name = $images_square_file->hashName();
            $file->size = $images_square_file->getSize();
            $file->mime = $images_square_file->getExtension();
            $file->url = $images_square;
            $file->save();
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
        $anime->descriptions = $request->descriptions;
        $anime->images = $images;
        $anime->images_square = $images_square;
        $anime->save();

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $anime], 200);
    }

    public function update(Request $request)
    {
        $data = Anime::find($request->id);
        if ($request->all() == null) {
            return response()->json(["status" => 1, "message" => "Nothing updated", "data" => $data], 200);
        }

        $data->update($request->all());

        $images_file = $request->file("images");
        $images = null;
        if ($images_file) {
            $images = Storage::disk("public")->put("/images", $images_file);

            $file = new File();
            $file->name = $images_file->hashName();
            $file->size = $images_file->getSize();
            $file->mime = $images_file->getExtension();
            $file->url = $images;
            $file->save();

            $data->update("images", $images);
        }

        $images_square_file = $request->file("images_square");
        $images_square = null;

        if ($images_square_file) {
            $images_square = Storage::disk("public")->put("/images", $images_square_file);

            $file = new File();
            $file->name = $images_square_file->hashName();
            $file->size = $images_square_file->getSize();
            $file->mime = $images_square_file->getExtension();
            $file->url = $images_square;
            $file->save();

            $data->update("images", $images_square);
        }

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $data], 200);
    }

    public function destroy(Request $request)
    {
        $data = Anime::findOrFail($request->id);
        $data->delete();

        return response()->json(["status" => 1, "message" => "Successfully"], 200);
    }
}
