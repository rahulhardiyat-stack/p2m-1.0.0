<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('referer')->nullable();
            $table->date('visited_date');
            $table->timestamps();
            $table->index('visited_date');
            $table->index('post_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
