<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesUserinfo extends Migration
{
    protected $table = 'userinfo';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create('userinfo', function (Blueprint $table) {
                //表的设计
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                //表的数据结构
                $table->increments('id')->comment('主键ID');
                $table->string('usercode')->comment('账号');
                $table->string('password')->comment('密码');
                $table->string('img_path')->nullable()->default(NULL)->comment('图片缩略图');
                $table->string('email')->nullable()->default(NULL)->comment('邮箱');
                $table->integer('iphone')->nullable()->default(NULL)->comment('手机');
                $table->string('birthday')->nullable()->default(NULL)->comment('生日');
                $table->integer('sex')->nullable()->default(3)->comment('性别1-男2-女3-未知');
                $table->integer('status')->default(1)->comment('状态-1-使用2-禁用-3-删除');
                $table->string('name')->nullable()->default('匿名')->comment('用户名称');
                $table->smallInteger('type')->nullable()->default(5)->comment('0-超级管理员1-管理员2-高级会员3-超级会员4-顶级会员5-会员');
                $table->dateTime('created_at')->comment('创建于');
                $table->dateTime('updated_at')->comment('更新于');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable($this->table) || \DB::table($this->table)->count() <1) {
            Schema::dropIfExists('userinfo');
        }

    }
}
