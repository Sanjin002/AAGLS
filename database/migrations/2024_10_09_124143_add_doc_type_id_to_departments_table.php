<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->string('pickup_city')->nullable();
            $table->string('pickup_street')->nullable();
            $table->string('pickup_zip')->nullable();
            $table->string('pickup_country')->nullable();
            $table->string('pickup_contact_name')->nullable();
            $table->string('pickup_contact_phone')->nullable();
            $table->string('pickup_contact_email')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_city', 'pickup_street', 'pickup_zip', 'pickup_country',
                'pickup_contact_name', 'pickup_contact_phone', 'pickup_contact_email'
            ]);
        });
    }
};
