<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'queued',       // waiting in line for their turn
                'active',       // allowed into checkout (15 min timer)
                'waitlisted',   // tickets sold out while in queue
                'notified',     // promoted from waitlist, email sent (60 min to claim)
                'processing',   // currently submitting payment (protected from expiry)
                'purchased',    // order successfully created
                'expired',      // timer ran out
                'canceled',     // user voluntarily left the queue
            ])->default('queued');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_active_at')->nullable(); // tracks heartbeat for inactivity detection
            $table->timestamps();

            $table->unique(['user_id', 'event_id']);
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
