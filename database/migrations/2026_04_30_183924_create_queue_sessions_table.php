<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //عبارة عن جدول بخزن فيه جلسات الطابور ليوم معين
    public function up(): void
    {
        Schema::create('queue_sessions', function (Blueprint $table) {
            $table->id();

        
            $table->foreignId('queue_id')->constrained()->onDelete('cascade');

            //اخر تيكت دور ضمن الطابور
            $table->integer('last_ticket_number')->default(0);

            $table->enum('status', [
                'active',   // شغال
                'inactive',   // متوقف مؤقت
            ])->default('active');

        
            $table->date("date");
            //لكل طابور جلسة وحدة باليوم
            $table->unique(["queue_id","date"]);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_sessions');
    }
};
