<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRebMlsListingLatitudeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reb_mls_listings', function ($table) {
            $table->decimal('latitude', 12,8)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reb_mls_listings', function ($table) {
            $table->decimal('latitude', 10,8)->nullable()->change();
        });
    }
}
