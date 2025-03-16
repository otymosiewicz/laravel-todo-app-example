<?php

use App\Enum\Priority;
use App\Enum\Status;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('priority', [Priority::LOW->value, Priority::MID->value, Priority::HIGH->value])->default(Priority::LOW->value);
            $table->enum('status', [Status::TODO->value, Status::IN_PROGRESS->value, Status::DONE->value])->default(Status::TODO->value);
            $table->dateTime('deadline')->nullable();
            $table->timestamps();

            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
