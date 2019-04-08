<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePersonnels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personnels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('emp_id',10);
            $table->string('last_name',50);
            $table->string('first_name',50);
            $table->string('middle_initial',3)->nullable();
            $table->string('department',35)->nullable();
            $table->string('job_position',50)->nullable();
            $table->string('email_add',40)->nullable();
            $table->string('photo_name',40)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('personnels');
    }
}
