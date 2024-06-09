<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_user_statuses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('user_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_statuses');
    }
}
