<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultPageOptionForPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_management', function (Blueprint $table) {
            $table->boolean('is_default')->default(false);
        });
    }

    public function down()
    {
        Schema::table('page_management', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
}
