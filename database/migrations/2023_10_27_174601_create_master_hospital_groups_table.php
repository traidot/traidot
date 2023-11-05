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
        Schema::create('master_hospital_groups', function (Blueprint $table) {
            $table->comment('病院グループ');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('hospital_id')->nullable()->comment('病院ID');
            $table->bigInteger('hospital_department_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('remark', 100)->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_hospital_groups');
    }
};
