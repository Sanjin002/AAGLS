<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parcels', function (Blueprint $table) {
            $table->id();
            $table->string('acKey')->unique();
            $table->string('ClientNumber');
            $table->string('ClientReference');
            $table->string('Content');
            $table->integer('Count');
            $table->decimal('CODAmount', 10, 2);
            $table->string('DeliveryCity');
            $table->string('DeliveryContactName');
            $table->string('DeliveryContactPhone');
            $table->string('DeliveryCountryIsoCode', 2);
            $table->string('DeliveryName');
            $table->string('DeliveryStreet');
            $table->string('DeliveryZipCode');
            $table->string('gls_parcel_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parcels');
    }
};