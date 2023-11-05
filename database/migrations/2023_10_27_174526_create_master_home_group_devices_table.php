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
        Schema::create('master_home_group_devices', function (Blueprint $table) {
            $table->comment('グループデバイス');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('home_group_id')->nullable()->comment('ホームグループID');
            $table->unsignedBigInteger('device_id')->nullable()->comment('デバイス');
            $table->boolean('is_disabled')->nullable()->default(false)->comment('停止フラグ
0: 有効
1: 停止');
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
        Schema::dropIfExists('master_home_group_devices');
    }
};
