<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tbl_products', function (Blueprint $table) {
        $table->increments('ID');
        $table->string('pName');
        $table->string('size');
        $table->string('brand');
        $table->integer('quantity');
        $table->float('price');
        $table->integer('availableQty');
        $table->string('category');
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
        Schema::dropIfExists('tbl_products');
    }
}
