<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produit_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('pharmacien_id')->nullable();
            $table->integer('quantite');
            $table->decimal('total', 10, 2);
            $table->dateTime('date_vente')->default(now());
            $table->timestamps();

            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('pharmacien_id')->references('id')->on('pharmaciens')->onDelete('set null');
});
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventes');
    }
}
