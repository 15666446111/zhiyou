<?php

namespace App\Admin\Controllers;

use App\Trade;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

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

        $grid->model()->latest();

        $grid->column('order', __('订单编号'))->copyable();
        //$grid->column('terminal', __('终端编号'))->copyable();
        $grid->column('merchant_id', __('商户编号'))->copyable();
        $grid->column('merchant_sn', __('SN'))->copyable();

        $grid->column('agt_merchant_id', __('渠道商ID'));
        $grid->column('agt_merchant_name', __('渠道商名称'));


        $grid->column('card_type', __('卡类型'));
        $grid->column('trade_type', __('交易类型'));
        $grid->column('trade_time', __('交易时间'));


        $grid->column('money', __('交易金额'))->display(function ($money) {
            return number_format($money/100, 2, '.', ',');
        })->label('info')->filter('range');

        //$grid->column('rate', __('交易费率'));
        $grid->column('rate_money', __('手续费'))->display(function ($money) {
            return number_format($money/100, 2, '.', ',');
        })->label('warning')->filter('range');

        $grid->column('real_money', __('结算金额'))->display(function ($money) {
            return number_format($money/100, 2, '.', ',');
        })->label('success')->filter('range');

        $grid->column('trade_status', __('交易'))->bool();

        $grid->column('is_cash', __('分润'))->bool();

        $grid->column('', '其他')->modal('处理结果', function ($model) {
            
            return new Table(['终端号', '商户编号名称','交易卡号','分润备注'], [[$model->terminal, $model->merchant_name,$model->card_number,$model->remark]]);
        });


        $grid->column('created_at', __('推送时间'));
        
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 去掉删除 编辑
            $actions->disableDelete();
            $actions->disableEdit();
        });
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->column(1/4, function ($filter) {
                $filter->like('order', '订单');
            });
            $filter->column(1/4, function ($filter) {
                $filter->like('merchant_sn', 'SN');
            });
            $filter->column(1/4, function ($filter) {
                $filter->like('merchant_id', '商户');
            });
            $filter->column(1/4, function ($filter) {
                $filter->equal('trade_status', '状态')->select(['0' => '失败', '1' => '成功']);
            });

            // 交易类型
            $filter->column(1/3, function ($filter) {
                $filter->equal('trade_type', '交易类型')->select([
                    'CLOUDPAY'      => '云闪付', 
                    'SMALLFREEPAY'  => '小额双免',
                    'VIPPAY'        => '激活交易',
                    'CARDPAYRVS'    => '消费冲正',
                    'CARDPAY'       => '刷卡消费',
                    'ENJOY'         => '优享',
                    'CARDCANCEL'    => '消费撤销',
                    'QUICKPAY'      => '快捷支付',
                    'WXQRPAY'       => '微信扫码',
                    'ALIQRPAY'      => '支付宝扫码',
                    'UNIONQRPAY'    => '银联扫码',
                    'CARDAUTH'      => '预授权',
                    'CARDCANCELAUTH'=> '预授权撤销',
                    'CARDAUTHED'    => '预授权完成',
                    'CARDCANCELAUTHED' => '预授权完成撤销',
                ]);
            });
            // 在这里添加字段过滤器
            $filter->column(1/3, function ($filter) {
                $filter->between('trade_time', '交易时间')->datetime();
            });
            
            $filter->column(1/3, function ($filter) {

                $arrs = array();

                $p = \App\Trade::select(['agt_merchant_name'])->distinct('agt_merchant_name')->pluck('agt_merchant_name')->toArray();
                
                foreach ($p as $key => $value) {
                   $arrs[$value] = $value;
                }

                $filter->equal('agt_merchant_name', '渠道商')->select($arrs);
            });
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

        $show->panel()->style('success')->title('推送交易订单详情...');
        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
            $tools->disableDelete();
        });


        $show->field('order', __('订单编号'));
        $show->field('batch_no', __('批次号'));
        $show->field('terminal_no', __('终端流水号'));
        $show->field('terminal', __('终端编号'));
        $show->field('merchant_id', __('商户编号'));
        $show->field('agt_merchant_id', __('渠道商户号'));
        $show->field('agt_merchant_name', __('渠道商名称'));
        $show->field('agt_merchant_level', __('渠道商级别'));

        $show->field('merchant_sn', __('终端SN'));
        $show->field('merchant_name', __('商户名称'));

        //$show->field('number', __('商户编号'));
        //
        $show->field('money', __('交易金额'))->as(function ($money) {
            return number_format($money / 100, 2, '.', ',');
        })->label();

        $show->field('rate', __('交易费率'))->as(function ($rate) {
            return $rate / 10000;
        })->label();

        $show->field('rate_money', __('手续费'))->as(function ($money) {
            return number_format($money / 100, 2, '.', ',');
        })->label();

        $show->field('fee_type', __('手续费类型'))->using(['1' => '非封顶', '2' => '封顶']);

        $show->field('real_money', __('结算金额'))->as(function ($money) {
            return number_format($money / 100, 2, '.', ',');
        })->label();

        $show->field('card_type', __('交易卡类型'));

        $show->field('card_number', __('交易卡号'));

        $show->field('trade_type', __('交易类型'));

        $show->field('collection_type', __('收款类型'));

        $show->field('trade_status', __('交易状态'))->using(['1' => '成功', '2' => '失败']);

        $show->field('audit_status', __('清算状态'))->using(['S' => '已清算', 'C' => '未清算', 'N'=> '未上传']);

        $show->field('is_sim', __('流量卡费'))->using(['0' => '正常交易', '1' => '全扣', '2'=> '内扣']);

        $show->field('stl_type', __('结算标示'))->using(['0' => 'TS', '1' => 'T1']);

        $show->field('scan_flag', __('正反扫标示'))->using(['POSITIVE' => '正扫', 'NEGATIVE' => '反扫', 'N' => '未上传']);

        $show->field('clr_flag', __('是否调价'))->using(['0' => '上调', '1' => '下调', ''=>'不调']);

        $show->field('is_auth_credit_card', __('是否本人卡'))->using(['0' => '否', '1' => '他人信用卡', '2'=> '本人认证信用卡']);

        $show->field('trade_time', __('交易时间'));

        $show->field('trade_actime', __('交易接收时间'));
        
        $show->field('is_cash', __('是否分润'))->using(['0' => '否', '1' => '是']);

        $show->field('remark', __('处理结果'));

        $show->field('created_at', __('推送接收时间'));

        //$show->field('updated_at', __('修改时间'));
        $show->cashs('分润返现', function ($cashs) {

            $cashs->setResource('/admin/cashs');
            
            $cashs->model()->latest();
            
            $cashs->id('索引')->sortable();
            $cashs->column('order', __('分润订单'));
            $cashs->column('users.nickname', __('分润会员'));
            $cashs->column('users.account', __('会员账号'));
            $cashs->column('cash_money', __('分润金额'))->display(function ($money) {
                return number_format($money / 100, 2, '.', ',');
            })->label();
            $cashs->column('cash_type', __('分润类型'))->using([
                '1' => '直营分润', '2' => '团队分润' , '3' => '直推分润' , '4' => '间推分润' ,  
                '5' => '激活返现', '6' => '直推激活' , '7' => '间推激活' , '8' => '团队激活'
            ]);
            $cashs->column('status', __('分润状态'))->bool();
            $cashs->column('remark', __('分润备注'));
            $cashs->column('created_at', __('分润时间'));

            $cashs->filter(function ($filter) {

                $filter->like('title', '标题');

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
        $form = new Form(new Trade());

        return $form;
    }
}
