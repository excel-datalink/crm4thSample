<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('estimate_id')->unsigned()->comment('見積ID');
            $table->string('product_name')->comment('商品名');
            $table->bigInteger('product_id')->unsigned()->nullable()->comment('商品ID');
            $table->decimal('unit_price', 13, 3)->comment('単価');
            $table->decimal('quantity', 13, 3)->comment('数量');
            $table->decimal('price', 13, 3)->comment('金額');
            $table->timestamps();

            $table->foreign('estimate_id')
                ->references('id')
                ->on('estimates');

            $table->foreign('product_id')
                ->references('id')
                ->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimate_details');
    }
}
