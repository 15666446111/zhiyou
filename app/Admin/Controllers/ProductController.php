<?php

namespace App\Admin\Controllers;

use App\Product;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->column('id', __('索引'));
        $grid->column('title', __('标题'));
        $grid->column('image', __('图片'));
        $grid->column('active', __('状态'))->switch();
        $grid->column('type', __('类型'));
        $grid->column('price', __('价格'))->label();
        $grid->column('created_at', __('创建时间'));
        
        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('image', __('Image'));
        $show->field('active', __('Active'));
        $show->field('type', __('Type'));
        $show->field('price', __('Price'));
        $show->field('content', __('Content'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product());

        $form->text('title', __('Title'));
        $form->image('image', __('Image'));
        $form->switch('active', __('Active'))->default(1);
        $form->switch('type', __('Type'));
        $form->number('price', __('Price'));
        $form->textarea('content', __('Content'));

        return $form;
    }
}
