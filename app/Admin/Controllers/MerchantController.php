<?php

namespace App\Admin\Controllers;

use App\Merchant;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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

        $grid->column('id', __('索引'));
        $grid->column('busers.nickname', __('归属会员'));
        $grid->column('merchant_terminal', __('终端编号'));
        $grid->column('brands.brand_name', __('终端品牌'));
        $grid->column('policys.title', __('政策活动'));
        $grid->column('merchant_number', __('商户编号'));
        $grid->column('merchant_name', __('商户名称'));
        $grid->column('user_phone', __('电话号码'));
        $grid->column('bind_status', __('绑定'))->bool();
        $grid->column('bind_time', __('绑定时间'));
        $grid->column('created_at', __('创建时间'));
        //$grid->column('updated_at', __('Updated at'));

        $grid->actions(function ($actions) {
            // 去掉删除 编辑
            $actions->disableDelete();
            $actions->disableEdit();

            // 发货按钮
            $actions->add(new DeliverGoods);
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

        $form->text('merchant_terminal', __('终端编号'));

        return $form;
    }
}
