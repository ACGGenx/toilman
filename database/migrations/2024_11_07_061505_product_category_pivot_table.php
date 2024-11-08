<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductCategoryPivotTable extends Migration
{
    public function up()
    {
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Copy existing category relationships using get() and foreach
        $products = DB::table('products')->whereNotNull('category_id')->get();
        foreach ($products as $product) {
            DB::table('category_product')->insert([
                'product_id' => $product->id,
                'category_id' => $product->category_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Remove the old category_id column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained();
        });

        // Restore the first category as the main category using get() and foreach
        $relations = DB::table('category_product')->get();
        foreach ($relations as $relation) {
            DB::table('products')
                ->where('id', $relation->product_id)
                ->update(['category_id' => $relation->category_id]);
        }

        Schema::dropIfExists('category_product');
    }
}
