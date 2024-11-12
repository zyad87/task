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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // اسم المهمة ويجب أن يكون فريداً
            $table->text('description')->nullable(); // وصف المهمة ويمكن أن يكون فارغاً
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending'); // حالة المهمة
            $table->timestamps(); // حقول created_at و updated_at
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
