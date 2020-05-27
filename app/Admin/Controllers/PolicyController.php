<?php

namespace App\Admin\Controllers;

use App\Policy;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PolicyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '活动政策';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Policy());

        $grid->column('id', __('索引'));
        $grid->column('title', __('活动'));
        $grid->column('active', __('状态'))->switch();
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
        $show = new Show(Policy::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('active', __('Active'));
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
        $form = new Form(new Policy());

        $form->tab('基础信息配置', function ($form) {
            $form->text('title', __('活动政策'));
            $form->switch('active', __('活动状态'))->default(1);
            $form->table('sett_price', '结算价设置',function ($table) {
                $table->text('trade_name', '类型名称');
                $table->text('trade_type', '交易类型');
                $table->text('trade_bank', '交易卡类型');
                $table->number('setprice', '结算价(万分位)')->default(0);
                $table->switch('open', '是否开启')->default(0);
            });
        })->tab('结算价设置', function ($form) {




        });

        return $form;
    }
}
