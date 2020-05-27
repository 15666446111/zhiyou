<?php

namespace App\Admin\Controllers;

use App\UserPolicy;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserPolicyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员政策信息';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserPolicy());

        $grid->column('id', __('索引'));
        $grid->column('busers.nickname', __('会员'));
        $grid->column('policys.title', __('政策'));


        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();
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
        $show = new Show(UserPolicy::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('policy_id', __('Policy id'));
        $show->field('sett_price', __('Sett price'));
        $show->field('active_return', __('Active return'));
        $show->field('standard', __('Standard'));
        $show->field('standard_count', __('Standard count'));
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
        $form = new Form(new UserPolicy());

            $form->table('sett_price', '结算价设置',function ($table) {
                $table->text('trade_name', '类型名称');
                $table->text('trade_type', '交易类型');
                $table->text('trade_bank', '交易卡类型');
                $table->number('setprice', '结算价(万分位)')->default(0);
                $table->switch('open', '是否开启')->default(0);
            });

        return $form;
    }
}
