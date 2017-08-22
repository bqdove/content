<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-01-15 20:36
 */
namespace Notadd\Content\Handlers\Page;

use Notadd\Content\Models\Page;
use Notadd\Content\Models\PageCategory;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class FetchHandler.
 */
class ListHandler extends Handler
{
    /**
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected $pagination;

    /**
     * Execute Handler.
     *
     * @throws \Exception
     */
    protected function execute()
    {
        $pagination = $this->request->input('pagination') ?: 10;
        if ($this->request->input('only-no-category')) {
            $this->pagination = Page::query()->where('category_id', 0)->paginate($pagination);
        } elseif ($id = $this->request->input('category')) {
            $categories = collect([(int)$id]);
            $this->container->make('log')->info('has category', $categories->toArray());
            PageCategory::query()->where('parent_id', $id)
                ->get()
                ->each(function (PageCategory $category) use ($categories) {
                    $categories->push($category->getAttribute('id'));
                    $children = PageCategory::query()->where('parent_id', $category->getAttribute('id'))->get();
                    $children->count() && $children->each(function (PageCategory $category) use ($categories) {
                        $categories->push($category->getAttribute('id'));
                        $children = (new PageCategory())->newQuery()->where('parent_id',
                            $category->getAttribute('id'))->get();
                        $children->count() && $children->each(function (PageCategory $category) use ($categories) {
                            $categories->push($category->getAttribute('id'));
                        });
                    });
                });
            $this->container->make('log')->info('get categories', $categories->toArray());
            $categories = $categories->unique();
            $this->container->make('log')->info('get categories', $categories->toArray());
            $this->pagination = Page::query()->whereIn('category_id', $categories->toArray())
                ->orderBy('created_at', 'desc')
                ->paginate($pagination);
        } else {
            $search = $this->request->input('search');
            if ($search) {
                $this->pagination = Page::query()->where('title', 'like',
                    '%' . $search . '%')->orWhere('content', 'like', '%' . $search . '%')->orderBy('created_at',
                    'desc')->paginate($pagination);
            } else {
                $this->pagination = Page::query()->orderBy('created_at', 'desc')->paginate($pagination);
            }
        }

        if ($this->pagination) {
            $this->withCode(200)
                ->withData($this->pagination->items())
                ->withMessage('content::page.fetch.success')
                ->withExtra([
                    'pagination' => [
                        'total'         => $this->pagination->total(),
                        'per_page'      => $this->pagination->perPage(),
                        'current_page'  => $this->pagination->currentPage(),
                        'last_page'     => $this->pagination->lastPage(),
                        'next_page_url' => $this->pagination->nextPageUrl(),
                        'prev_page_url' => $this->pagination->previousPageUrl(),
                        'from'          => $this->pagination->firstItem(),
                        'to'            => $this->pagination->lastItem(),
                    ],
                ]);
        }
    }
}
