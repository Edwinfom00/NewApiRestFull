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
        Schema::create('interns', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('company_id');
            $table->string('title');
            $table->string('slug');
            $table->text('description');
            $table->text('roles')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('position');
            $table->string('address');
            $table->string('company_name')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_location')->nullable();
            $table->string('type')->nullable();
            $table->integer('status')->nullable();
            $table->date('last_date');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('max_places')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interns');
    }
};
