<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->comment('ユーザID');
            $table->string('name')->comment('顧客名');
            $table->string('address1')->nullable()->comment('住所1');
            $table->string('address2')->nullable()->comment('住所2');
            $table->string('tel')->nullable()->comment('TEL');
            $table->string('fax')->nullable()->comment('FAX');
            $table->string('payment_term')->comment('支払条件');
            $table->bigInteger('version')->default(0)->comment('Version');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
