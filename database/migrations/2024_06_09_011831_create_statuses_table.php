<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_statuses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // Puede ser 'photo' o 'video'
            $table->string('content'); // URL o ruta del archivo
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
