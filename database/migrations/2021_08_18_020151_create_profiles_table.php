<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->comment('ユーザID');
            $table->string('company_name')->comment('会社名');
            $table->string('postal_code')->nullable()->comment('郵便番号');
            $table->string('address1')->nullable()->comment('住所1');
            $table->string('address2')->nullable()->comment('住所2');
            $table->string('tel')->nullable()->comment('TEL');
            $table->string('fax')->nullable()->comment('FAX');
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
        Schema::dropIfExists('profiles');
    }
}
