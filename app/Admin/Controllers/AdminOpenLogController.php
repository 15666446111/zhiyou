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

        $grid->column('id', __('索引'));
        $grid->column('admin_users.username', __('管理员'));
        $grid->column('path', __('路径'))->label('info');
        $grid->column('method', __('请求方式'))->label();
        $grid->column('ip', __('Ip地址'))->label('primary');
        $grid->column('input', __('操作'))->style('wide:50px');
        $grid->column('created_at', __('操作时间'));

        $grid->actions(function ($actions) {
            // 去掉编辑
            $actions->disableEdit();

            // 去掉删除
            $actions->disableDelete();
        });

        $grid->disableCreateButton();

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
        $show = new Show(AdminOpenLog::findOrFail($id));

        $show->field('id', __('索引'));
        $show->field('admin_users.username', __('管理员'));
        $show->field('path', __('路径'))->label('info');
        $show->field('method', __('请求方式'))->label();
        $show->field('ip', __('Ip地址'))->label('primary');
        $show->field('input', __('操作'))->style('wide:50px');
        $show->field('created_at', __('操作时间'));

        $show->panel()->tools(function ($tools) {

            $tools->disableDelete();

            $tools->disableEdit();
            
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
        $form = new Form(new AdminOpenLog());

        $form->number('user_id', __('User id'));
        $form->text('path', __('Path'));
        $form->text('method', __('Method'));
        $form->ip('ip', __('Ip'));
        $form->textarea('input', __('Input'));

        return $form;
    }
}
