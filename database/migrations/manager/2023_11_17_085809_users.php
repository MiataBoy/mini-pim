
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username', 16)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token')->unique();
            $table->bigInteger('profile_id')->default('0')->nullable();
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->string('locale')->default('nl');
            $table->string('company');
            $table->string('defaultPage')->default('Users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
