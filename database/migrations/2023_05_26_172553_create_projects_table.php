<?php

use App\Enums\ProjectStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->foreignUuid('user_id')->constrained()->delete('cascade');
            $table->foreignUuid('client_id')->constrained()->delete('cascade');
            $table->date('deadline');
            $table->enum('status', array_column(ProjectStatus::cases(), 'name'));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
