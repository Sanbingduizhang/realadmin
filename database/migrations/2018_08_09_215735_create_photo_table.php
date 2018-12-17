<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoTable extends Migration
{
    protected $table = 'photo';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create('photo', function (Blueprint $table) {
                //表的设计
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                //表的数据结构
                $table->increments('id')->comment('主键ID');
                $table->integer('photocateid')->comment('分类id');
                $table->integer('userid')->comment('用户id');
                $table->string('img_thumb')->comment('缩略图');
                $table->string('img_path')->comment('图片路径');
                $table->string('img_name')->comment('图片现在名称');
                $table->string('img_origin')->nullable()->default(NULL)->comment('图片原名称');
                $table->string('type')->nullable()->default(NULL)->comment('图片真实类型');
                $table->string('ext')->nullable()->default(NULL)->comment('后缀名');
                $table->integer('likecount')->nullable()->default(0)->comment('点赞数量');
                $table->integer('pv')->nullable()->default(0)->comment('观看数目');
                $table->smallInteger('is_del')->nullable()->default(1)->comment('是否删除1-未删除2-删除');
                $table->dateTime('created_at')->nullable()->comment('创建于');
                $table->dateTime('updated_at')->nullable()->comment('更新于');
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
            Schema::dropIfExists('photo');
        }

    }
}
