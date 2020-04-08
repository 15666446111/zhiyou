<?php

namespace App\Admin\Controllers;

use App\Cash;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CashController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Cash';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Cash());

        $grid->column('id', __('索引'));
        $grid->column('order', __('分润订单'));
        $grid->column('user_id', __('分润会员'));
        $grid->column('cash_money', __('分润金额'));
        $grid->column('status', __('分润状态'));
        $grid->column('remark', __('分润备注'));
        $grid->column('created_at', __('分润时间'));
        //$grid->column('updated_at', __('Updated at'));
        
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 去掉删除 编辑
            $actions->disableDelete();
            $actions->disableEdit();
        });
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
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
        $show = new Show(Cash::findOrFail($id));

        $show->field('order', __('分润订单'));
        $show->field('user_id', __('分润会员'));
        $show->field('cash_money', __('分润金额'));
        $show->field('status', __('分润状态'));
        $show->field('remark', __('分润备注'));
        $show->field('created_at', __('分润时间'));
        //$show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Cash());

        $form->text('order', __('Order'));
        $form->number('user_id', __('User id'));
        $form->number('cash_money', __('Cash money'));
        $form->number('status', __('Status'))->default(1);
        $form->text('remark', __('Remark'));

        return $form;
    }
}
