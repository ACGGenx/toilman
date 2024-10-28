<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToSliderImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('slider_images', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('image_path');  // Add the `order` column after `image_path`
        });
    }

    public function down()
    {
        Schema::table('slider_images', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
