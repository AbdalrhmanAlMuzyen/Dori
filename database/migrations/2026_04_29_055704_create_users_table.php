<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId("role_id")->constrained("roles")->onDelete("cascade");
            $table->string("first_name");
            $table->string("last_name");
            $table->string("email")->unique();
            $table->string("password");
            $table->dateTime("email_verified_at")->nullable();
            $table->enum("status",["active","inactive","blocked"])->default("inactive");
            $table->string("verification_token")->nullable();
            $table->dateTime("expires_at")->nullable();
            $table->integer("token_version")->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
