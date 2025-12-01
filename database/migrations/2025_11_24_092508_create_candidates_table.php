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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('reg');
            $table->string('name')->nullable();
            $table->string('cadre_category');
            $table->string('district')->nullable();
            $table->string('gender')->nullable();
            $table->integer('general_merit_position')->nullable();
            $table->integer('technical_merit_position')->nullable();
            $table->string('technical_passed_cadres')->nullable();
            $table->string('choice_list');
            $table->string('choice_list_tech')->nullable();
            $table->boolean('has_quota')->default(0);
            $table->string('quota_info')->nullable();
            $table->string('higher_choices')->nullable();
            $table->string('lower_choices')->nullable();
            $table->string('assigned_cadre')->nullable();
            $table->string('assigned_status')->nullable();
            $table->string('allocation_status')->nullable();
            $table->string('general_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
