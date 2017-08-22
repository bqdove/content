<?php
/**
 * This file is part of Notadd.
 *
 * @datetime 2017-01-22 12:02
 */

use Illuminate\Database\Schema\Blueprint;
use Notadd\Foundation\Database\Migrations\Migration;

/**
 * Class CreateArticlesTable.
 */
class CreateContentArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('content_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('source_author')->nullable();
            $table->string('source_link')->nullable();
            $table->string('thumb_image')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('keyword')->nullable();
            $table->string('description')->nullable();
            $table->integer('hits')->default(0);
            $table->boolean('is_hidden')->default(0);
            $table->tinyInteger('is_sticky')->default(0);
            $table->string('flow_marketing')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists('content_articles');
    }
}
