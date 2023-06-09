<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\File;
use App\Models\Genre;
use App\Models\Season;
use App\Models\Status;
use App\Models\Studio;
use App\Models\Type;
use App\Models\Video;
use App\Models\Viewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->search) {
            $data = Video::where("title", "LIKE", "%" . $request->search . "%")->orderBy("updated_at", $request->direction == "ASC" ? "asc": "desc")->get();

            return response()->json(["status" => 1, "data" => $this->formatData($data)], 200);
        }

        if ($request->slug) {
            $data = Video::where("slug", $request->slug)->get();


            if ($request->record) {
                if ($data->first()) {
                    $next = Video::where("id", ">", $data->first()->id)->orderBy("id", "desc")->get()->first();
                    $previous = Video::where("id", "<", $data->first()->id)->orderBy("id", "desc")->get()->first();
                    return response()->json(["status" => 1, "data" => $this->formatData($data), "record" => ["next" => $this->formatSingleData($next), "previous" => $this->formatSingleData($previous)]], 200);
                }
            }
            return response()->json(["status" => 1, "data" => $this->formatData($data),], 200);
        }

        if ($request->anime_id) {
            $data = Video::where("anime", $request->anime_id)->get();

            return response()->json(["status" => 1, "data" => $this->formatData($data)], 200);
        }

        $data = Video::all()->sortBy("updated_at", $request->direction == "ASC" ? SORT_ASC: SORT_DESC);
        $data_f = $this->formatData($data);
        return response()->json(["status" => 1, "data" => $data_f], 200);
    }

    function formatData($data)
    {
        foreach ($data as $key => $value) {
            $data[$key]->anime = $this->formatAnimeData(Anime::find($value->anime));
            $data[$key]->viewer = count(Viewer::where("video", $value->id)->get());
        }
        return $data;
    }

    function formatSingleData($data)
    {
        if ($data != null) {
            $data->anime = $this->formatAnimeData(Anime::find($data->anime));
            $data->viewer = count(Viewer::where("video", $data->id)->get());
            return $data;
        }
    }

    function formatAnimeData($data)
    {
        $data->type = Type::find($data->type);
        $data->status = Status::find($data->status);
        $data->studio = Studio::find($data->studio);
        $data->season = Season::find($data->season);
        $genres_decod = json_decode($data->genres);
        $genre_list = array();
        foreach ($genres_decod as $genre) {
            $genre_list[] = Genre::find($genre);
        }
        $data->genres = $genre_list;

        return $data;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|string",
            "slug" => "required|string",
            "anime" => "required|integer",
            "descriptions" => "required|string",
            "images" => "required|file",
            "videos" => "required|file",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        $image_file = $request->file("images");
        $images = null;

        if ($image_file) {
            $images = Storage::disk("public")->put("/images", $image_file);

            $file = new File();
            $file->name = $image_file->hashName();
            $file->size = $image_file->getSize();
            $file->mime = $image_file->getExtension();
            $file->url = $images;
            $file->save();
        }

        $video_file = $request->file("videos");
        $videos = null;

        if ($video_file) {
            $videos = Storage::disk("public")->put("/videos", $video_file);

            $file = new File();
            $file->name = $video_file->hashName();
            $file->size = $video_file->getSize();
            $file->mime = $video_file->getExtension();
            $file->url = $videos;
            $file->save();
        }

        $video = new Video;
        $video->title = $request->title;
        $video->slug = $request->slug;
        $video->anime = $request->anime;
        $video->descriptions = $request->descriptions;
        $video->images = $images;
        $video->videos = $videos;
        $video->save();

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $video], 200);
    }

    public function update(Request $request)
    {
        $data = Video::find($request->id);
        if ($request->all() == null) {
            return response()->json(["status" => 1, "message" => "Nothing updated", "data" => $data], 200);
        }

        $data->update($request->all());

        $image_file = $request->file("images");

        if ($image_file) {
            $images = Storage::disk("public")->put("/images", $image_file);

            $file = new File();
            $file->name = $image_file->hashName();
            $file->size = $image_file->getSize();
            $file->mime = $image_file->getExtension();
            $file->url = $images;
            $file->save();

            $data->update(["images" => $images]);
        }

        $video_file = $request->file("videos");

        if ($video_file) {
            $videos = Storage::disk("public")->put("/videos", $video_file);

            $file = new File();
            $file->name = $video_file->hashName();
            $file->size = $video_file->getSize();
            $file->mime = $video_file->getExtension();
            $file->url = $videos;
            $file->save();

            $data->update(["videos" => $videos]);
        }

        return response()->json(["status" => 1, "message" => "Successfully", "data" => $data], 200);
    }

    public function destroy(Request $request)
    {
        $data = Video::findOrFail($request->id);
        $data->delete();

        return response()->json(["status" => 1, "message" => "Successfully"], 200);
    }
}
