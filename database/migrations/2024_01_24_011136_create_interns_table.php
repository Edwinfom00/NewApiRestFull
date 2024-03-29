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
            $table->integer('featured')->nullable();
            $table->string('type')->nullable();
            // ['fulltime','parttime','contract','internship'];
            $table->integer('status')->nullable();
            $table->date('last_date');
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
