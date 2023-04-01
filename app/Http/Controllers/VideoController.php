<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index()
    {
        $data = Video::all();
        return response()->json(["status" => 1, "data" => $data], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|string",
            "slug" => "required|string",
            "anime" => "required|integer",
            "images" => "required|string",
            "videos" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => 0, "message" => $validator->errors()], 200);
        }

        $video = new Video;
        $video->title = $request->title;
        $video->slug = $request->slug;
        $video->anime = $request->anime;
        $video->images = $request->images;
        $video->videos = $request->videos;
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
