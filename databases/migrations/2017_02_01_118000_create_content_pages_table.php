<?php
/**
 * This file is part of Notadd.
 *
 * @datetime 2017-01-22 18:10:17
 */

use Illuminate\Database\Schema\Blueprint;
use Notadd\Foundation\Database\Migrations\Migration;

/**
 * Class CreatePagesTable.
 */
class CreateContentPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema->create('content_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->integer('category_id')->default(0);
            $table->string('title');
            $table->string('thumb_image')->nullable();
            $table->string('alias')->nullable();
            $table->string('keyword')->nullable();
            $table->string('description')->nullable();
            $table->string('template')->nullable();
            $table->text('content')->nullable();
            $table->boolean('enabled')->default(true);
            $table->bigInteger('hits')->default(0);
            $table->tinyInteger('order_id')->default(0);
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
        $this->schema->dropIfExists('content_pages');
    }
}
