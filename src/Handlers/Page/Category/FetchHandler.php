<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, iBenchu.org
 * @datetime 2017-02-15 14:48
 */
namespace Notadd\Content\Handlers\Page\Category;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;
use Notadd\Content\Models\PageCategory;
use Notadd\Foundation\Passport\Abstracts\DataHandler;

/**
 * Class CategoryFetcherHandler.
 */
class FetchHandler extends DataHandler
{
    /**
     * CategoryFinderHandler constructor.
     *
     * @param \Illuminate\Container\Container     $container
     * @param \Notadd\Content\Models\PageCategory $category
     * @param \Illuminate\Http\Request            $request
     * @param \Illuminate\Translation\Translator  $translator
     */
    public function __construct(
        Container $container,
        PageCategory $category,
        Request $request,
        Translator $translator
    ) {
        parent::__construct($container, $request, $translator);
        $this->model = $category;
    }

    /**
     * Http code.
     *
     * @return int
     */
    public function code()
    {
        return 200;
    }

    /**
     * Data for handler.
     *
     * @return array
     */
    public function data()
    {
        if ($this->request->input('with-children')) {
            $categories = $this->model->newQuery()->orderBy('order_id', 'asc')->get();
            $categories->transform(function (PageCategory $category) {
                $children = $this->model->newQuery()->where('parent_id', $category->getAttribute('id'))->orderBy('order_id', 'asc')->get();
                $children->count() && $category->setAttribute('children', $children);
                return $category;
            });
            return $categories;
        }
        if ($this->hasFilter) {
            return $this->model->get();
        } else {
            return $this->model->structure();
        }
    }

    /**
     * Errors for handler.
     *
     * @return array
     */
    public function errors()
    {
        return [
            $this->translator->trans('content::category.fetch.fail'),
        ];
    }

    /**
     * Messages for handler.
     *
     * @return array
     */
    public function messages()
    {
        return [
            $this->translator->trans('content::category.fetch.success'),
        ];
    }
}
