<?php

namespace App\Admin\Controllers;

use App\Share;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ShareController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '分享素材管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Share());
        $grid->model()->latest();

        $grid->column('id', __('索引'));
        $grid->column('title', __('分享标题'));
        $grid->column('image', __('分享素材'));
        $grid->column('types.name', __('分享类型'))->label();
        $grid->column('share_text', __('分享文案'));
        $grid->column('code_width', __('二维码宽度'));
        $grid->column('code_height', __('二维码高度'));
        $grid->column('code_margin', __('二维码边距'));
        $grid->column('pos_x', __('X轴定位'));
        $grid->column('pos_y', __('Y轴定位'));
        $grid->column('active', __('状态'))->bool();
        $grid->column('created_at', __('创建时间'));
        //$grid->column('updated_at', __('Updated at'));
        //
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->column(1/4, function ($filter) {
                $filter->like('title', '标题');
            });
            // 在这里添加字段过滤器
            
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
        $show = new Show(Share::findOrFail($id));

        $show->field('title', __('分享标题'));
        $show->field('image', __('分享素材'));
        $show->field('type', __('分享类型'));
        $show->field('share_text', __('分享文案'));
        $show->field('code_width', __('二维码宽度'));
        $show->field('code_height', __('二维码高度'));
        $show->field('code_margin', __('二维码边距'));
        $show->field('pos_x', __('X轴定位'));
        $show->field('pos_y', __('Y轴定位'));
        $show->field('active', __('活动状态'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('修改时间'));

        $show->types('分类信息', function ($type) {
            $type->name('类型名称');
            $type->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
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
        $form = new Form(new Share());

        $form->text('title', __('分享标题'));
        $form->image('image', __('分享素材'));
        $form->select('type', __('分享类型'))->options(\App\ShareType::get()->pluck('name', 'id'));
        $form->text('share_text', __('分享文案'));
        $form->number('code_width', __('二维码宽度'))->default(100);
        $form->number('code_height', __('二维码高度'))->default(100);
        $form->number('code_margin', __('二维码边距'))->default(0);
        $form->number('pos_x', __('X轴定位'))->default(200);
        $form->number('pos_y', __('Y轴定位'))->default(700);
        $form->switch('active', __('活动状态'))->default(1);

        return $form;
    }
}
