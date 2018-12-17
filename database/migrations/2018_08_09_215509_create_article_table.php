<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    protected $table = 'article';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create('article', function (Blueprint $table) {
                //表的设计
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                //表的数据结构
                $table->increments('id')->comment('主键ID');
                $table->integer('userid')->comment('用户的id');
                $table->string('title')->comment('文章名称');
                $table->string('desc')->nullable()->default(NULL)->comment('文章的描述');
                $table->string('content')->nullable()->default(NULL)->comment('文章内容，存储地址');
                $table->integer('cateid')->comment('文章的分类');
                $table->smallInteger('publish')->nullable()->default(1)->comment('是否发布1-发布2-不发布');
                $table->integer('like')->nullable()->default(0)->comment('点赞数');
                $table->integer('pv')->nullable()->default(0)->comment('观看数');
                $table->smallInteger('is_rec')->nullable()->default(2)->comment('是否推荐1-推荐2-不推荐');
                $table->integer('wordsnum')->nullable()->nullable()->default(0)->comment('文章数目');
                $table->smallInteger('status')->nullable()->nullable()->default(1)->comment('审核状态1-通过2-不通过');
                $table->smallInteger('is_pv_use')->nullable()->default(1)->comment('观看权限1-所有人2-指定人3-自己');
                $table->smallInteger('is_del')->nullable()->default(1)->comment('是否删除1-不删除0-删除');
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
            Schema::dropIfExists('article');
        }

    }
}
