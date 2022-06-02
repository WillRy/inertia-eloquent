<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string("name");
            $table->string("email");
            $table->date("date_birth");
            $table->decimal("weight");
            $table->decimal("height");
            $table->enum("gender", ["m","f","o"]);

            $table->unsignedBigInteger("tenant_id");
            $table->foreign("tenant_id")->references("id")->on("tenants")->onDelete("CASCADE");


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
        Schema::dropIfExists('students');
    }
};
