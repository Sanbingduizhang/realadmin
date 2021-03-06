<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoReplayTable extends Migration
{
    protected $table = 'photo_reply';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create('photo_reply', function (Blueprint $table) {
                //表的设计
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                //表的数据结构
                $table->increments('id')->comment('主键ID');
                $table->integer('userid')->comment('用户id');
                $table->integer('pcomid')->comment('图片回复的评论id');
                $table->text('content')->nullable()->default(NULL)->comment('评论内容');
                $table->integer('likecount')->nullable()->default(0)->comment('点赞数');
                $table->integer('replynum')->nullable()->default(0)->comment('评论数');
                $table->integer('pid')->nullable()->default(0)->comment('回复的父id，默认0代表对评论的回复，其余的是对回复的回复');
                $table->smallInteger('is_del')->nullable()->default(1)->comment('是否删除1-不删除2-删除');
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
            Schema::dropIfExists('photo_reply');
        }

    }
}
