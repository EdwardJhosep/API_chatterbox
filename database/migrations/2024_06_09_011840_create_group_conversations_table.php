<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_group_conversations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupConversationsTable extends Migration
{
    public function up()
    {
        Schema::create('group_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_conversations');
    }
}
