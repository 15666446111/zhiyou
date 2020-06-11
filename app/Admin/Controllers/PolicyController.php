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
        $show = new Show(Policy::findOrFail($id));

        $show->field('title', __('政策活动'));
        $show->field('active', __('状态'))->using(['0' => '关闭', '1' => '正常']);

        $show->field('default_push', __('直推分润比例'));

        $show->field('indirect_push', __('间推分润比例'));

        $show->field('default_active', __('直推激活奖励'));

        $show->field('indirect_active', __('间推激活奖励'));

        $show->field('sett_price', __('结算价设置'))->as(function ($content) {
            return json_encode($content);
        })->json();

        $show->field('default_active_set', __('普通会员激活'))->as(function ($content) {
            return json_encode($content);
        })->json();
        $show->field('vip_active_set', __('代理会员激活'))->as(function ($content) {
            return json_encode($content);
        })->json();

        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));


        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
        });


        $show->merchants('机具详情', function ($merchants) {

            $merchants->setResource('/admin/merchants');
            
            $merchants->model()->latest();
            
            $merchants->id('索引')->sortable();

            $merchants->column('busers.nickname', __('归属会员'));

            $merchants->column('merchant_terminal', __('终端编号'));

            $merchants->column('merchant_sn', __('终端SN'));

            $merchants->column('policys.title', __('政策活动'));

            $merchants->column('merchant_number', __('商户编号'));

            $merchants->column('merchant_name', __('商户名称'));

            $merchants->column('user_phone', __('电话号码'));

            $merchants->column('bind_status', __('绑定'))->bool();

            $merchants->column('bind_time', __('绑定时间'));

            $merchants->column('active_status', __('激活'))->bool();

            $merchants->column('active_time', __('激活时间'));

            $merchants->created_at('创建时间')->date('Y-m-d H:i:s');

            $merchants->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                $filter->column(1/3, function ($filter) {
                    $filter->like('merchant_terminal', '终端编号');
                });
                $filter->column(1/3, function ($filter) {
                    $filter->like('merchant_name', '商户名称');
                });
                $filter->column(1/3, function ($filter) {
                    $filter->equal('bind_status', '商户名称')->select(['0' => '未绑定', '1' => '已绑定']);
                });

            });

            $merchants->actions(function ($actions) {
                // 去掉删除 编辑
                $actions->disableDelete();
                $actions->disableEdit();
            });
            
            $merchants->batchActions(function ($batch) {
                $batch->disableDelete();
            });

        });

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

            $form->table('default_standard_set', '普通用户达标设置',function ($table) {
                
                $table->text('standard_name', '达标名称')->required();

                $table->select('standard_type', '达标类型')->options(['1' => '连续达标', '2' => '累积达标'])->required();

                $table->number('standard_start', '日期(起)')->default(0);

                $table->number('standard_end', '日期(止)')->default(0);

                $table->number('standard_trade', '满足交易')->default(0);

                $table->number('standard_price', '本人奖励')->default(0);

                $table->number('standard_parent_price', '上级奖励')->default(0);

            });

            $form->table('vip_standard_set', '代理用户达标设置',function ($table) {

                $table->text('standard_name', '达标名称')->required();

                $table->select('standard_type', '达标类型')->options(['1' => '连续达标', '2' => '累积达标'])->required();

                $table->number('standard_start', '日期(起)')->default(0);

                $table->number('standard_end', '日期(止)')->default(0);

                $table->number('standard_trade', '满足交易')->default(0);

                $table->number('standard_price', '达标奖励')->default(0);

            });

        });

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });
        
        return $form;
    }
}
