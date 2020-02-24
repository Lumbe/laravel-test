<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRebMlsListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reb_mls_listings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->jsonb('mls')->nullable()->default('{}');
            $table->float('price')->nullable();
            $table->jsonb('address')->nullable()->default('{}');
            $table->jsonb('location')->nullable()->default('{}');
            $table->decimal('latitude', 10,8)->nullable();
            $table->decimal('longitude',12,8)->nullable();
            $table->jsonb('general')->nullable()->default('{}');
            $table->jsonb('aditional')->nullable()->default('{}');
            $table->jsonb('schools')->nullable()->default('{}');
            $table->string('thumbnail')->nullable()->default('');
            $table->jsonb('images')->nullable()->default('[]');
            $table->longText('overview')->nullable()->default('');
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
        Schema::dropIfExists('reb_mls_listings');
    }
}
