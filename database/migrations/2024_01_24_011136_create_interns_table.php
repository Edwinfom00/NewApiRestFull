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
            $table->text('roles');
            $table->integer('category_id');
            $table->string('position');
            $table->string('address');
            $table->integer('featured');
            $table->string('type');
            // ['fulltime','parttime','contract','internship'];
            $table->integer('status');
            $table->date('last_date');
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
