<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-08 18:30
 */
namespace Notadd\Content\Listeners;

use Notadd\Content\Controllers\Api\Article\ArticleController as ArticleControllerForAdministration;
use Notadd\Content\Controllers\Api\Article\ArticleInformationController as ArticleInformationControllerForAdministration;
use Notadd\Content\Controllers\Api\Article\DraftController as ArticleDraftControllerForAdministration;
use Notadd\Content\Controllers\Api\Category\CategoryController as CategoryControllerForAdministration;
use Notadd\Content\Controllers\Api\Page\PageController as PageControllerForAdministration;
use Notadd\Content\Controllers\Api\Page\CategoryController as PageCategoryControllerForAdministration;
use Notadd\Content\Controllers\Api\UploadController;
use Notadd\Content\Controllers\ArticleController;
use Notadd\Content\Controllers\CategoryController;
use Notadd\Content\Controllers\ComponentController;
use Notadd\Content\Controllers\PageController;
use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;

/**
 * Class RouteRegister.
 */
class RouteRegister extends AbstractRouteRegister
{
    /**
     * Handle Route Register.
     */
    public function handle()
    {
        $this->router->group(['middleware' => ['auth:api', 'cross', 'web']], function () {
            $this->router->group(['prefix' => 'api/content/article'], function () {
                $this->router->post('auto', ArticleControllerForAdministration::class . '@auto');
                $this->router->post('create', ArticleControllerForAdministration::class . '@create');
                $this->router->post('edit', ArticleControllerForAdministration::class . '@edit');
                $this->router->post('remove', ArticleControllerForAdministration::class . '@remove');
                $this->router->post('batchMove', ArticleControllerForAdministration::class . '@batchMove');
                $this->router->group(['prefix' => 'draft'], function () {
                    $this->router->post('/', ArticleDraftControllerForAdministration::class . '@draft');
                    $this->router->post('create', ArticleDraftControllerForAdministration::class . '@create');
                    $this->router->post('list', ArticleDraftControllerForAdministration::class . '@list');
                    $this->router->post('remove', ArticleDraftControllerForAdministration::class . '@remove');
                });
                $this->router->group(['prefix' => 'information'], function () {
                    $this->router->post('/', ArticleInformationControllerForAdministration::class . '@information');
                    $this->router->post('create', ArticleInformationControllerForAdministration::class . '@create');
                    $this->router->post('edit', ArticleInformationControllerForAdministration::class . '@edit');
                    $this->router->post('list', ArticleInformationControllerForAdministration::class . '@list');
                    $this->router->post('remove', ArticleInformationControllerForAdministration::class . '@remove');
                });
            });
            $this->router->group(['prefix' => 'api/content/category'], function () {
                $this->router->post('create', CategoryControllerForAdministration::class . '@create');
                $this->router->post('edit', CategoryControllerForAdministration::class . '@edit');
                $this->router->post('remove', CategoryControllerForAdministration::class . '@remove');
                $this->router->post('sort', CategoryControllerForAdministration::class . '@sort');
            });
            $this->router->group(['prefix' => 'api/content/component'], function () {
                $this->router->post('get', ComponentController::class . '@get');
                $this->router->post('set', ComponentController::class . '@set');
            });
            $this->router->group(['prefix' => 'api/content/page'], function () {
                $this->router->post('/', PageControllerForAdministration::class . '@page');
                $this->router->post('create', PageControllerForAdministration::class . '@create');
                $this->router->post('edit', PageControllerForAdministration::class . '@edit');
                $this->router->post('list', PageControllerForAdministration::class . '@list');
                $this->router->post('remove', PageControllerForAdministration::class . '@remove');
                $this->router->group(['prefix' => 'category'], function () {
                    $this->router->post('/', PageCategoryControllerForAdministration::class . '@category');
                    $this->router->post('create', PageCategoryControllerForAdministration::class . '@create');
                    $this->router->post('edit', PageCategoryControllerForAdministration::class . '@edit');
                    $this->router->post('list', PageCategoryControllerForAdministration::class . '@list');
                    $this->router->post('remove', PageCategoryControllerForAdministration::class . '@remove');
                    $this->router->post('sort', PageCategoryControllerForAdministration::class . '@sort');
                });
            });
            $this->router->post('api/content/upload', UploadController::class . '@handle');
        });
        $this->router->group(['middleware' => ['cross', 'web']], function () {
            $this->router->group(['prefix' => 'api/content/article'], function () {
                $this->router->post('/', ArticleControllerForAdministration::class . '@article');
                $this->router->post('list', ArticleControllerForAdministration::class . '@list');
            });
            $this->router->group(['prefix' => 'api/content/category'], function () {
                $this->router->post('/', CategoryControllerForAdministration::class . '@category');
                $this->router->post('list', CategoryControllerForAdministration::class . '@list');
            });
        });
        $this->router->group(['middleware' => 'web', 'prefix' => 'content'], function () {
            $this->router->get('/', function () {
                return $this->container->make('view')->make('content::content');
            });
            $this->router->resource('article', ArticleController::class);
            $this->router->resource('category', CategoryController::class);
            $this->router->resource('page', PageController::class);
            $this->router->resource('about', PageController::class);
        });
    }
}
