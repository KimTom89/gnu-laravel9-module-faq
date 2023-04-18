<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq_categories', function (Blueprint $table) {
            $table->comment('g5_faq_master => faq_categories');
            $table->id();
            $table->string('subject')->default('');
            $table->text('head_html')->nullable();
            $table->text('tail_html')->nullable();
            $table->text('mobile_head_html')->nullable();
            $table->text('mobile_tail_html')->nullable();
            $table->string('image_head')->nullable();
            $table->string('image_tail')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq_categories');
    }
};
