<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ViewerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get("/animes", [AnimeController::class, "index"]);
Route::post("/animes", [AnimeController::class, "store"]);
Route::delete("/animes", [AnimeController::class, "destroy"]);

Route::get("/videos", [VideoController::class, "index"]);
Route::post("/videos", [VideoController::class, "store"]);
Route::delete("/videos", [VideoController::class, "destroy"]);

Route::get("/viewer", [ViewerController::class, "index"]);
Route::delete("/viewer", [ViewerController::class, "destroy"]);

Route::get("/files", [FileController::class, "index"]);
Route::post("/files", [FileController::class, "store"]);
Route::delete("/files", [FileController::class, "destroy"]);

Route::get("/types", [TypeController::class, "index"]);
Route::post("/types", [TypeController::class, "store"]);
Route::delete("/types", [TypeController::class, "destroy"]);

Route::get("/genres", [GenreController::class, "index"]);
Route::post("/genres", [GenreController::class, "store"]);
Route::delete("/genres", [GenreController::class, "destroy"]);

Route::get("/seasons", [SeasonController::class, "index"]);
Route::post("/seasons", [SeasonController::class, "store"]);
Route::delete("/seasons", [SeasonController::class, "destroy"]);

Route::get("/statuses", [StatusController::class, "index"]);
Route::post("/statuses", [StatusController::class, "store"]);
Route::delete("/statuses", [StatusController::class, "destroy"]);

Route::get("/studios", [StudioController::class, "index"]);
Route::post("/studios", [StudioController::class, "store"]);
Route::delete("/studios", [StudioController::class, "destroy"]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
