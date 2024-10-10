<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('department_doc_type', function (Blueprint $table) {
        $table->id();
        $table->foreignId('department_id')->constrained()->onDelete('cascade');
        $table->foreignId('doc_type_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('department_doc_type');
}
};
