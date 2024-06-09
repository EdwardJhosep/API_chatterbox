<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_group_participants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupParticipantsTable extends Migration
{
    public function up()
    {
        Schema::create('group_participants', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_conversation_id')->constrained('group_conversations')->onDelete('cascade');
            $table->primary(['user_id', 'group_conversation_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_participants');
    }
}
