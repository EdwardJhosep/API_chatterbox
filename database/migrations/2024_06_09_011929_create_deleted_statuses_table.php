<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_deleted_statuses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('deleted_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deleted_statuses');
    }
}
