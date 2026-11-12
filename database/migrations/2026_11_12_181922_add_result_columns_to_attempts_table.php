<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            $table->decimal('percentage',5,2)->nullable()->after('score');
            $table->boolean('passed')->nullable()->after('percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
        $table->dropColumn(['percentage', 'passed']);
    });
    }
};
