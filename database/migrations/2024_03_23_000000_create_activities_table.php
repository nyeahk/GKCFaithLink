<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->string('icon');
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}; 