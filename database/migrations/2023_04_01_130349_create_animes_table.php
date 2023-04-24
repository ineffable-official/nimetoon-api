<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('animes', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("slug")->unique();
            $table->foreignId("type");
            $table->integer("episodes");
            $table->foreignId("status");
            $table->date("aired_from");
            $table->date("aired_to");
            $table->foreignId("season");
            $table->foreignId("studio");
            $table->json("genres");
            $table->string("descriptions");
            $table->string("images");
            $table->string("images_square");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
