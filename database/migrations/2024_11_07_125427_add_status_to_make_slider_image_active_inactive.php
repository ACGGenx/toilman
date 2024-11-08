<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToMakeSliderImageActiveInactive extends Migration
{
    public function up()
    {
        Schema::table('slider_images', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->string('url')->nullable();
        });
    }

    public function down()
    {
        Schema::table('slider_images', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'url']);
        });
    }
}
