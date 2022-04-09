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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("student_id");
            $table->foreign("student_id")->references("id")->on("students");

            $table->unsignedBigInteger("plan_id");
            $table->foreign("plan_id")->references("id")->on("plans");

            $table->date("dt_start");
            $table->date("dt_end");

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
        Schema::dropIfExists('table_subscriptions');
    }
};
