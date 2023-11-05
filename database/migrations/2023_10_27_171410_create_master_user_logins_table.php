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
        Schema::create('master_user_logins', function (Blueprint $table) {
            $table->comment('ユーザログイン情報');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('platform')->nullable()->default(0)->comment('ログインプラットフォーム:0: email/pwログイン 1: lineログイン 2: google 3: apple');
            $table->string('identifier')->nullable()->unique('identifer')->comment('ログインID');
            $table->string('password')->nullable()->default('"NULL"')->comment('ログインパスワード');
            $table->string('push_token')->nullable()->default('"NULL"')->comment('プッシュトークン');
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
        Schema::dropIfExists('master_user_logins');
    }
};
