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
        Schema::create('queues', function (Blueprint $table) {
            $table->foreignId('event_id')->constrained()->onDelete('set null');
            $table->id();
            $table->enum('status', ['waiting','canceled', 'completed'])->default('waiting');
            $table->timestamps();
            $table->primary(['event_id','id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
