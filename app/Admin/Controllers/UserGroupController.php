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

        $grid->column('name', __('用户组名称'));
        
        $grid->column('level', __('用户组级别'))->label();

        $grid->column('created_at', __('创建时间'));

        $grid->actions(function ($actions) {
            if($actions->getKey() <= 2){
                $actions->disableDelete();
                $actions->disableEdit();
            }
        });

        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });

        $grid->disableFilter();

        //$grid->disableCreateButton();

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
        //$show->field('count', __('推荐多少有效用户升级'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('修改时间'));

        $show->users('用户信息', function ($users) {


            $users->setResource('/admin/busers');
            
            $users->model()->latest();

            $users->column('id', __('索引'))->sortable();
            $users->column('nickname', __('昵称'));
            $users->column('account', __('账号'));
            $users->column('realname', __('姓名'));
            $users->column('phone', __('手机号'));
            $users->column('headimg', __('头像图片'))->image('', 60);

            $users->column('wallets.cash_blance', __('分润余额'))->display(function ($money) {
                return number_format($money/100, 2, '.', ',');
            })->label('info');
            $users->column('wallets.return_blance', __('返现余额'))->display(function ($money) {
                return number_format($money/100, 2, '.', ',');
            })->label('warning');
            $users->column('wallets.blance_active', __('钱包'))->bool();

            $users->column('active', __('状态'))->bool();

            $users->column('last_ip', __('最后登录地址'));

            $users->column('last_time', __('最后登录时间'));

            $users->created_at('注册时间');

            $users->disableCreateButton();
            $users->actions(function ($actions) {
                // 去掉删除 编辑
                $actions->disableDelete();
                $actions->disableEdit();
            });
            $users->batchActions(function ($batch) {
                $batch->disableDelete();
            });
        });

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
        $form = new Form(new UserGroup());

        $form->text('name',     __('用户组名称'));

        $form->number('level',  __('用户组级别'))->readonly()->disable()->help('此处不可调整');

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
            $tools->disableView();
        });

        return $form;
    }
}
