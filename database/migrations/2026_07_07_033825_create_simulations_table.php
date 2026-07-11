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
        Schema::create('simulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('gas_type', ['amonia', 'klorin']);
            $table->integer('duration'); // in seconds
            $table->double('max_ppm');
            $table->double('final_ppm');
            $table->enum('status', ['survived', 'failed']);
            $table->string('failure_reason')->nullable();
            $table->string('ppe_selected');
            $table->enum('mitigation_action', ['water_spray', 'capping_kit']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulations');
    }
};
