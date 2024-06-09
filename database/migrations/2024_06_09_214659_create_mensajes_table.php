<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMensajesTable extends Migration
{
    public function up()
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_origen');
            $table->string('numero_destino');
            $table->text('mensaje')->nullable();
            $table->string('foto_nombre')->nullable();
            $table->string('foto_ruta')->nullable();
            $table->string('video_nombre')->nullable();
            $table->string('video_ruta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mensajes');
    }
}
