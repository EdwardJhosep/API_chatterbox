<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadosTable extends Migration
{
    public function up()
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('numero_actual');
            $table->text('estado')->nullable();
            $table->string('foto_ruta')->nullable();
            $table->string('video_ruta')->nullable();
            $table->unsignedInteger('likes')->default(0);
            $table->unsignedInteger('vistas')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estados');
    }
}
