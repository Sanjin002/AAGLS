<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('parcels', function (Blueprint $table) {
            $table->longBlob('gls_response')->nullable();
            $table->timestamp('label_expiry')->nullable();
        });
    }

    public function down()
    {
        Schema::table('parcels', function (Blueprint $table) {
            $table->dropColumn(['gls_response', 'label_expiry']);
        });
    }
};