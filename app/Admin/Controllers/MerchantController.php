<?php

namespace App\Admin\Controllers;

use App\Merchant;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

use App\Admin\Actions\DeliverGoods;
use App\Admin\Actions\ImportMachines;
use App\Admin\Actions\MachineHeadTail;
use App\Admin\Actions\ImportDeliverGoods;
use App\Admin\Actions\HeadTailDeliverGoods;

use Encore\Admin\Controllers\AdminController;

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

        $grid->model()->latest();

        $grid->column('id', __('索引'));
        $grid->column('busers.nickname', __('归属会员'));
        $grid->column('merchant_terminal', __('终端编号'));
        $grid->column('merchant_sn', __('终端SN'));
        $grid->column('brands.brand_name', __('终端品牌'));
        $grid->column('policys.title', __('政策活动'));
        $grid->column('merchant_number', __('商户编号'));
        $grid->column('merchant_name', __('商户名称'));
        $grid->column('user_phone', __('电话号码'));
        $grid->column('bind_status', __('绑定'))->bool();
        $grid->column('bind_time', __('绑定时间'));
        $grid->column('active_status', __('激活'))->bool();
        $grid->column('active_time', __('激活时间'));        
        $grid->column('created_at', __('创建时间'));
        //$grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
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

        $grid->actions(function ($actions) {
            // 去掉删除 编辑
            $actions->disableDelete();
            $actions->disableEdit();

            // 如果机器未发货 显示发货按钮
            if($actions->row['user_id'] == 0) $actions->add(new DeliverGoods);
        });
        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });

        $grid->tools(function ($tools) {

            $tools->append(new ImportMachines());

            $tools->append(new MachineHeadTail());

            $tools->append(new ImportDeliverGoods());

            $tools->append(new HeadTailDeliverGoods());
            
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

        $show->field('user_phone', __('登记电话'));

        $show->field('merchant_number', __('商户编号'));

        $show->field('merchant_terminal', __('终端编号'));

        $show->field('merchant_sn', __('终端SN'));

        $show->field('active_status', __('激活状态'))->using(['0' => '未激活' , '1' => '已激活']);

        $show->field('active_time', __('激活时间'));

        $show->field('merchant_name', __('商户名称'));

        $show->field('merchant_phone', __('商户电话'));

        $show->field('bind_status', __('绑定状态'))->using(['0' => '未绑定' , '1' => '已绑定']);

        $show->field('bind_time', __('绑定时间'));

        $show->field('standard_statis', __('达标状态'))->using(['0' => '默认' , '1' => '连续达标', '-1'=> '达标终端']);

        $show->field('created_at', __('创建时间'));


        $show->policys('政策信息', function ($policys) {
            $policys->title('政策标题');
            $policys->active('状态')->using(['0'=> '关闭', '1' => '正常']);
            $policys->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });
        });

        $show->tradess_sn('交易信息', function ($trade) {

            $trade->setResource('/admin/trades');
            
            $trade->model()->latest();
            
            $trade->id('索引')->sortable();

            $trade->column('order', __('订单编号'))->copyable();
            $trade->column('terminal', __('终端编号'))->copyable();
            $trade->column('merchant_id', __('商户编号'))->copyable();
            $trade->column('merchant_sn', __('SN'))->copyable();

            $trade->column('agt_merchant_id', __('渠道商ID'));
            $trade->column('agt_merchant_name', __('渠道商名称'));


            $trade->column('card_type', __('卡类型'));
            $trade->column('trade_type', __('交易类型'));
            $trade->column('trade_time', __('交易时间'));


            $trade->column('money', __('交易金额'))->display(function ($money) {
                return number_format($money/100, 2, '.', ',');
            })->label('info')->filter('range');

            //$grid->column('rate', __('交易费率'));
            $trade->column('rate_money', __('手续费'))->display(function ($money) {
                return number_format($money/100, 2, '.', ',');
            })->label('warning')->filter('range');

            $trade->column('real_money', __('结算金额'))->display(function ($money) {
                return number_format($money/100, 2, '.', ',');
            })->label('success')->filter('range');

            $trade->column('trade_status', __('交易'))->bool();

            $trade->column('is_cash', __('分润'))->bool();

            $trade->column('', '其他')->modal('处理结果', function ($model) {
                
                return new Table(['商户编号名称','交易卡号','分润备注'], [[$model->merchant_name,$model->card_number,$model->remark]]);
            
            });

            $trade->column('created_at', __('推送时间'));

            $trade->disableCreateButton();
            $trade->actions(function ($actions) {
                // 去掉删除 编辑
                $actions->disableDelete();
                $actions->disableEdit();
            });
            $trade->batchActions(function ($batch) {
                $batch->disableDelete();
            });

            $trade->filter(function($filter){
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
                // 在这里添加字段过滤器
                
            });

        });



        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
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
        $form = new Form(new Merchant());

        $form->text('merchant_sn', __('终端SN'));

        $form->select('brand_id', __('所属品牌'))->options(\App\Brand::where('active', '1')->get()->pluck('brand_name', 'id'));

        return $form;
    }
}
