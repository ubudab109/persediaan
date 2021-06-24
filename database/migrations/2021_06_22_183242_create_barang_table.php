<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->unsignedBigInteger('kategori_id')->index();
            $table->foreign('kategori_id')->on('kategori_barang')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('satuan_id')->index();
            $table->foreign('satuan_id')->on('satuan_barang')->references('id')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama');
            $table->text('deskripsi');
            $table->double('harga');
            $table->integer('stock')->default(0);
            $table->string('gambar');
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
        Schema::dropIfExists('barang');
    }
}
