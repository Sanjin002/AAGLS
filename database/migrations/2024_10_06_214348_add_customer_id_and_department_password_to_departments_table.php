<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->string('customer_id')->nullable();
            $table->string('department_password')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(['customer_id', 'department_password']);
        });
    }
};
