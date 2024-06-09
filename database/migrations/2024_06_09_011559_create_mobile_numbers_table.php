<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_mobile_numbers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileNumbersTable extends Migration
{
    public function up()
    {
        Schema::create('mobile_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('number')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mobile_numbers');
    }
}
