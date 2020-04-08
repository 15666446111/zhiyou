<?php

namespace App\Admin\Controllers;

use App\Withdraw;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WithdrawController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '提现申请管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Withdraw());

        $grid->column('id', __('索引'));
        $grid->column('user_id', __('提现会员'));
        $grid->column('money', __('提现金额'));
        $grid->column('real_money', __('到账金额'));
        $grid->column('rate', __('提现费率'));
        $grid->column('rate_money', __('手续费'));
        $grid->column('single_rate', __('单笔提现费'));
        $grid->column('status', __('提现状态'));
        $grid->column('pay_time', __('审核时间'));
        $grid->column('remark', __('提现备注'));
        $grid->column('created_at', __('申请时间'));
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
        $show = new Show(Withdraw::findOrFail($id));

        $show->field('user_id', __('User id'));
        $show->field('money', __('Money'));
        $show->field('real_money', __('Real money'));
        $show->field('rate', __('Rate'));
        $show->field('rate_money', __('Rate money'));
        $show->field('single_rate', __('Single rate'));
        $show->field('status', __('Status'));
        $show->field('pay_time', __('Pay time'));
        $show->field('remark', __('Remark'));
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
        $form = new Form(new Withdraw());

        $form->number('user_id', __('User id'));
        $form->number('money', __('Money'));
        $form->number('real_money', __('Real money'));
        $form->number('rate', __('Rate'));
        $form->number('rate_money', __('Rate money'));
        $form->number('single_rate', __('Single rate'));
        $form->number('status', __('Status'));
        $form->datetime('pay_time', __('Pay time'))->default(date('Y-m-d H:i:s'));
        $form->text('remark', __('Remark'));

        return $form;
    }
}
