<?php

namespace App\Admin\Controllers;

use App\Merchant;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MerchantController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商户编号管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Merchant());

        $grid->column('id', __('索引'));
        $grid->column('user_id', __('归属会员'));
        $grid->column('user_phone', __('电话号码'));
        $grid->column('merchant_number', __('商户编号'));
        $grid->column('merchant_terminal', __('终端编号'));
        $grid->column('merchant_name', __('商户名称'));
        $grid->column('bind_status', __('绑定状态'));
        $grid->column('bind_time', __('绑定时间'));
        $grid->column('created_at', __('创建时间'));
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
        $show = new Show(Merchant::findOrFail($id));

        $show->field('user_id', __('归属会员'));
        $show->field('user_phone', __('电话号码'));
        $show->field('merchant_number', __('商户编号'));
        $show->field('merchant_terminal', __('终端编号'));
        $show->field('merchant_name', __('商户名称'));
        $show->field('bind_status', __('绑定状态'));
        $show->field('bind_time', __('绑定时间'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Merchant());

        $form->number('user_id', __('User id'));
        $form->text('user_phone', __('User phone'));
        $form->text('merchant_number', __('Merchant number'));
        $form->text('merchant_terminal', __('Merchant terminal'));
        $form->text('merchant_name', __('Merchant name'));
        $form->switch('bind_status', __('Bind status'));
        $form->datetime('bind_time', __('Bind time'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
