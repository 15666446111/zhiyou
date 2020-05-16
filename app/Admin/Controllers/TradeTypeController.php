<?php

namespace App\Admin\Controllers;

use App\TradeType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TradeTypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '交易类型';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TradeType());

        $grid->column('type_name', __('类型名称'));
        $grid->column('type_value', __('类型编号'));
        $grid->column('is_rewetting', __('是否返润'))->switch();
        $grid->column('created_at', __('创建时间'));
        
        $grid->header(function ($query) {
            return '<span style="color:red">只有是否返润为是的交易类型的订单才会获得系统返润,未存在或者是否返润为否的交易类型订单不获得返润</span>';
        });

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            
            $filter->column(1/3, function ($filter) {
                // 在这里添加字段过滤器
                $filter->like('type_name', '名称')->placeholder('请输入要查询的类型名称');
            });

            $filter->column(1/3, function ($filter) {
                // 在这里添加字段过滤器
                $filter->like('type_value', '编号')->placeholder('请输入要查询的类型编号');
            });

            $filter->column(1/3, function ($filter) {
                // 在这里添加字段过滤器
                $filter->in('is_rewetting', '返润')->checkbox([ '0' => '不返润', '1'  => '返润' ]);
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
        $show = new Show(TradeType::findOrFail($id));

        $show->field('type_name', __('类型名称'));
        $show->field('type_value', __('类型编号'));
        $show->field('is_rewetting', __('是否返润'));
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
        $form = new Form(new TradeType());

        $form->text('type_name', __('类型名称'));
        $form->text('type_value', __('类型编号'));
        $form->switch('is_rewetting', __('是否返润'));

        return $form;
    }
}
