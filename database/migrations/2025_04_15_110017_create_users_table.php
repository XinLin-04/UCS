<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('email')->unique(); // Unique email addres
            $table->string('password'); // Password field
            $table->enum('role', ['user', 'admin'])->default('user'); // User role (user or admin)
            $table->rememberToken(); //for remember me functionality, cookie based authentication
            $table->timestamps(); //created_at and updated_at timestamps
            $table->timestamp('email_verified_at')->nullable(); // Email verification timestamp
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
}
