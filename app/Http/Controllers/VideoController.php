<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\File;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->search) {
            $data = Video::where("title", "LIKE", "%" . $request->search . "%")->get();

            return response()->json(["status" => 1, "data" => $this->formatData($data)], 200);
        }

        if ($request->slug) {
            $data = Video::where("slug", $request->slug)->get();

            return response()->json(["status" => 1, "data" => $this->formatData($data)], 200);
        }

        if ($request->anime_id) {
            $data = Video::where("anime", $request->anime_id)->get();

            return response()->json(["status" => 1, "data" => $this->formatData($data)], 200);
        }

        $data = Video::all();
        $data_f = $this->formatData($data);
        return response()->json(["status" => 1, "data" => $data_f], 200);
    }

    function formatData($data)
    {
        foreach ($data as $key => $value) {
            $data[$key]->anime = Anime::find($value->anime);
        }
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

    public function destroy(Request $request)
    {
        $data = Video::findOrFail($request->id);
        $data->delete();

        return response()->json(["status" => 1, "message" => "Successfully"], 200);
    }
}
