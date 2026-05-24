<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("queue_session_id")->constrained("queue_sessions")->onDelete("cascade");
            $table->integer("number");
            $table->enum('status', [
                'waiting',     
                'called',       
                'serving',     
                'served',       
                'not_served',  
                'cancelled'
            ])->default('waiting');
            
            $table->timestamp('called_at')->nullable();

            $table->timestamp('served_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
