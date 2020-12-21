<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('envio')->nullable();
            $table->string('pago')->nullable();
            $table->string('status')->default('pendiente');
            $table->double('iva')->nullable();
            $table->double('discount_web')->nullable();
            $table->double('discount_web_per')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('subtotaliva')->nullable();
            $table->double('total')->nullable();
            $table->string('mensaje')->nullable();
            $table->boolean('terminos')->nullable();
            $table->string('file')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
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
        Schema::dropIfExists('transactions');
    }
}
