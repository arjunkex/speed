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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('api_id');
            $table->string('image')->default('images/plans/default.png');
            $table->string('name');
            $table->double('amount');
            $table->string('currency');
            $table->string('interval');
            $table->string('product_id');
            $table->string('description')->nullable();
            $table->integer('limit_clients')->default(0)->comment('0 means unlimited');
            $table->integer('limit_suppliers')->default(0)->comment('0 means unlimited');
            $table->integer('limit_employees')->default(0)->comment('0 means unlimited');
            $table->integer('limit_domains')->default(0)->comment('0 means unlimited');
            $table->integer('limit_purchases')->default(0)->comment('0 means unlimited');
            $table->integer('limit_invoices')->default(0)->comment('0 means unlimited, and here invoice means sales limit, ex: sales->invoice');
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
        Schema::dropIfExists('plans');
    }
};
