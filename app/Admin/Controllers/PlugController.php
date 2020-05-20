<?php

namespace App\Admin\Controllers;

use App\Plug;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PlugController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '轮播图管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Plug());

        $grid->column('id', __('索引'));
        $grid->column('image_file', __('文件'))->image('', 50, 50);
        $grid->column('link', __('链接'))->link();
        $grid->column('active', __('状态'))->bool();
        $grid->column('sort', __('排序'))->label();
        $grid->column('created_at', __('创建时间'));
        //$grid->column('updated_at', __('修改时间'));

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
        $show = new Show(Plug::findOrFail($id));

        $show->field('image_file', __('图片文件'))->image();
        $show->field('link', __('链接地址'))->link();
        $show->field('active', __('活动状态'))->using(['0' => '关闭', '1' => '开启']);
        $show->field('sort', __('排序权重'))->label();
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
        $form = new Form(new Plug());

        $form->file('image_file', __('上传图片'));

        $form->url('link', __('链接地址'));

        $form->switch('active', __('开启状态'));
        
        $form->number('sort', __('排序权重'))->default(0);

        return $form;
    }
}
