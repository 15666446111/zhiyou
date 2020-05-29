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

            $form->switch('active', __('活动状态'))->default(1)->help('关闭活动状态时,配送无法选择此活动,已配送机器分润等不受影响');

            $form->number('default_push', __('会员直推'))->default(2)->help('当机器持有人为普通用户的时候,该用户获得的交易分润推荐比例');
            $form->number('indirect_push', __('会员间推'))->default(1)->help('当机器持有人为普通用户的时候,该用户上级临近的代理获得的交易分润推荐比例');

            $form->table('sett_price', '结算价设置',function ($table) {
                $table->text('trade_name', '类型名称');
                $table->text('trade_type', '交易类型');
                $table->text('trade_bank', '交易卡类型');
                $table->number('setprice', '最低结算价(万分位)')->default(0);
                $table->number('defaultPrice', '默认结算价(万分位)')->default(0);
                $table->switch('open', '是否开启')->default(0);
            });
            
        })->tab('激活返现设置', function ($form) {

            $form->number('default_active', __('直推激活'))->default(2)->help('机器激活,上级获得的直推奖励.(单位为分)');
            $form->number('indirect_active', __('会员间推'))->default(1)->help('机器激活,上上级获得的间推奖励.(单位为分)');

            $form->fieldset('用户激活返现', function (Form $form) {
                $form->embeds('default_active_set', '用户激活',function ($form) {
                    $form->number('return_money', '最高返现')->default(0)->rules('required')->help('(单位为分)');
                    $form->number('default_money', '默认返现')->default(0)->rules('required')->help('(单位为分)');
                });
            });

            $form->fieldset('代理激活返现', function (Form $form) {
                $form->embeds('vip_active_set', '代理激活',function ($form) {
                    $form->number('return_money', '最高返现')->default(0)->rules('required')->help('(单位为分)');
                    $form->number('default_money', '默认返现')->default(0)->rules('required')->help('(单位为分)');
                });
            });

        })->tab('达标奖励设置', function ($form) {


        });

        return $form;
    }
}
