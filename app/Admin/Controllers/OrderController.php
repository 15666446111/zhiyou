<?php

namespace App\Admin\Controllers;

use App\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('id', __('索引'));
        $grid->column('order_no', __('订单编号'));
        $grid->column('user_id', __('下单会员'));
        $grid->column('product_id', __('订单产品'));
        $grid->column('product_price', __('产品单价'));
        $grid->column('numbers', __('订单数量'));
        $grid->column('price', __('订单价格'));
        $grid->column('address', __('配送地址'));
        $grid->column('status', __('订单状态'));
        $grid->column('remark', __('订单备注'));
        $grid->column('created_at', __('创建时间'));
        $grid->actions(function ($actions) {
 
            //关闭行操作 删除 
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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_no', __('Order no'));
        $show->field('user_id', __('User id'));
        $show->field('product_id', __('Product id'));
        $show->field('product_price', __('Product price'));
        $show->field('numbers', __('Numbers'));
        $show->field('price', __('Price'));
        $show->field('address', __('Address'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Order());

        $form->text('order_no', __('Order no'));
        $form->text('user_id', __('User id'));
        $form->text('product_id', __('Product id'));
        $form->text('product_price', __('Product price'));
        $form->text('numbers', __('Numbers'));
        $form->text('price', __('Price'));
        $form->text('address', __('Address'));
        $form->text('status', __('Status'));
        $form->text('remark', __('Remark'));

        return $form;
    }
}
