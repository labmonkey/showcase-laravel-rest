<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('storeNumber');
            $table->string('storeName');
	        $table->string('address');
	        $table->string('siteId');
	        $table->float('lat');
	        $table->float('lon');
	        $table->string('phoneNumber');
	        $table->boolean('cfsFlag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
