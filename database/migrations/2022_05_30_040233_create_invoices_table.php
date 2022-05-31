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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users'); //how create    
            $table->string('invoice_number')->uniqid;
            $table->dateTime('order_date');
            $table->longText('billing_address');
            $table->longText('shipping_address');
            $table->decimal('total_amount',10,2)->nullable();
            $table->decimal('sub_total_amount',10,2)->nullable();
            $table->decimal('discount_amount',10,2)->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
