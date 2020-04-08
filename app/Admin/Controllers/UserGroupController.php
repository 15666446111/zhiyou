<?php

namespace App\Admin\Controllers;

use App\UserGroup;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserGroupController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户组管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserGroup());

        $grid->column('id', __('索引'));
        $grid->column('name', __('用户组名称'));
        $grid->column('level', __('用户组级别'));
        $grid->column('count', __('推荐多少有效用户升级'));
        $grid->column('created_at', __('创建时间'));
        //$grid->column('updated_at', __('Updated at'));
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
        $show = new Show(UserGroup::findOrFail($id));

        $show->field('name', __('用户组名称'));
        $show->field('level', __('用户组级别'));
        $show->field('count', __('推荐多少有效用户升级'));
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
        $form = new Form(new UserGroup());

        $form->text('name',     __('用户组名称'));
        $form->number('level',  __('用户组级别'))->default(1);
        $form->number('count',  __('推荐多少有效用户升级'))->default(0);

        return $form;
    }
}
