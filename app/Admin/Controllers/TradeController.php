<?php

namespace App\Admin\Controllers;

use App\Trade;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TradeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '终端交易管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Trade());

        $grid->column('id', __('索引'));
        $grid->column('order', __('订单编号'));
        $grid->column('terminal', __('终端编号'));
        $grid->column('number', __('商户编号'));
        $grid->column('money', __('交易金额'));
        $grid->column('rate', __('交易费率'));
        $grid->column('rate_money', __('手续费'));
        $grid->column('real_money', __('结算金额'));
        $grid->column('trade_status', __('交易状态'));
        $grid->column('created_at', __('创建时间'));
        //$grid->column('updated_at', __('修改时间'));
        
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
        $show = new Show(Trade::findOrFail($id));

        $show->field('order', __('订单编号'));
        $show->field('terminal', __('终端编号'));
        $show->field('number', __('商户编号'));
        $show->field('money', __('交易金额'));
        $show->field('rate', __('交易费率'));
        $show->field('rate_money', __('手续费'));
        $show->field('real_money', __('结算金额'));
        $show->field('trade_status', __('交易状态'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('修改时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Trade());

        $form->text('order', __('Order'));
        $form->text('terminal', __('Terminal'));
        $form->text('number', __('Number'));
        $form->number('money', __('Money'));
        $form->number('rate', __('Rate'));
        $form->number('rate_money', __('Rate money'));
        $form->number('real_money', __('Real money'));
        $form->switch('trade_status', __('Trade status'))->default(1);

        return $form;
    }
}
