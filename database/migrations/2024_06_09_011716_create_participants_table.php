<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_participants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->primary(['user_id', 'conversation_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('participants');
    }
}
