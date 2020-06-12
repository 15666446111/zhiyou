<?php

namespace App\Admin\Controllers;

use App\AdminOpenLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AdminOpenLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '操作日志';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdminOpenLog());

        $grid->model()->latest();

        $grid->column('id', __('id'));
        $grid->column('admin_users.username', __('管理员'));
        $grid->column('path', __('路径'))->label('info');
        $grid->column('method', __('请求方式'))->label();
        $grid->column('ip', __('Ip地址'))->label('primary');
        $grid->column('input', __('Input'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->actions(function ($actions) {
            // 去掉编辑
            $actions->disableEdit();

            // 去掉查看
            $actions->disableView();
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
        $show = new Show(AdminOpenLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('path', __('Path'));
        $show->field('method', __('Method'));
        $show->field('ip', __('Ip'));
        $show->field('input', __('Input'));
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
        $form = new Form(new AdminOpenLog());

        $form->number('user_id', __('User id'));
        $form->text('path', __('Path'));
        $form->text('method', __('Method'));
        $form->ip('ip', __('Ip'));
        $form->textarea('input', __('Input'));

        return $form;
    }
}
