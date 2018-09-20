<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleLikecountTable extends Migration
{
    protected $table = 'article_likecount';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create('article_likecount', function (Blueprint $table) {
                //表的设计
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                //表的数据结构
                $table->increments('id')->comment('主键ID');
                $table->integer('userid')->comment('用户id');
                $table->integer('pid')->comment('文章相关的图片，评论，回复id');
                $table->smallInteger('likego')->nullable()->default(1)->comment('是否点赞1-点赞2-取消赞（直接删除');
                $table->smallInteger('type')->nullable()->default(1)->comment('1-文章，2-文章评论，3-文章回复');
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
            Schema::dropIfExists('article_likecount');
        }

    }
}
