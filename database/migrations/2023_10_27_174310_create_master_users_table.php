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
        Schema::create('master_users', function (Blueprint $table) {
            $table->comment('ユーザ');
            $table->bigIncrements('id');
            $table->integer('role')->nullable()->default(0)->comment('ユーザ権限 0: 管理者 1: 一般ユーザ 2: 企業オペレーター 3: 病院オペレーター 4: 企業ユーザ 5: 病院ユーザ');
            $table->string('access_token')->nullable()->default('"NULL"')->comment('アクセストークン アプリのAPI認証で利用');
            $table->string('lastname', 32)->nullable()->comment('姓');
            $table->string('firstname', 32)->nullable()->comment('名');
            $table->string('lastname_kana', 32)->nullable()->default('"NULL"')->comment('セイ');
            $table->string('firstname_kana', 32)->nullable()->default('"NULL"')->comment('メイ');
            $table->unsignedInteger('gender')->nullable()->comment('性別 0: その他 1: 男性 2: 女性');
            $table->date('birthday')->nullable()->comment('生年月日');
            $table->string('email')->nullable()->default('"NULL"')->comment('メールアドレス');
            $table->string('tel', 16)->nullable()->default('"NULL"')->comment('電話番号');
            $table->string('zip_code', 8)->nullable()->default('"NULL"')->comment('郵便番号');
            $table->string('address', 64)->nullable()->default('"NULL"')->comment('住所');
            $table->dateTime('disable_at')->nullable()->comment('停止日時');
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
        Schema::dropIfExists('master_users');
    }
};
