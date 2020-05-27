<?php

namespace App\Admin\Controllers;

use App\Buser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BuserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Buser());

        $grid->column('id', __('索引'));
        $grid->column('nickname', __('昵称'));
        $grid->column('account', __('账号'));
        $grid->column('realname', __('姓名'));
        $grid->column('phone', __('手机号'));
        $grid->column('headimg', __('头像图片'));
        $grid->column('groups.name', __('级别'))->label();
        $grid->column('wallets.cash_blance', __('分润余额'))->label();
        $grid->column('wallets.return_blance', __('返现余额'))->label();
        $grid->column('wallets.blance_active', __('钱包状态'))->bool();
        $grid->column('active', __('状态'))->bool();
        $grid->column('last_ip', __('最后登录IP'));
        $grid->column('last_time', __('最后登录时间'));
        $grid->column('created_at', __('注册时间'));

        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();
        });

        $grid->batchActions(function ($batch) {
            $batch->disableDelete();
        });
        
        //$grid->disableCreateButton();

        // 查询过滤器
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->column(1/4, function ($filter) {
                $filter->like('nickname', '昵称');
            });
            $filter->column(1/4, function ($filter) {
                $filter->like('account',  '账号');
            });
            $filter->column(1/4, function ($filter) {
                $filter->like('phone',    '手机');
            });
            $filter->column(1/4, function ($filter) {
                $filter->like('realname', '姓名');
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
        $show = new Show(Buser::findOrFail($id));

        $show->field('nickname', __('用户昵称'));
        $show->field('account', __('用户账号'));
        $show->field('realname', __('真实姓名'));
        $show->field('phone', __('手机号码'));
        $show->field('headimg', __('用户头像'));
        $show->field('parent', __('上级会员'));
        $show->field('group', __('用户级别'));
        $show->field('blance', __('用户余额'));
        $show->field('score', __('用户积分'));
        $show->field('active', __('用户状态'));
        $show->field('blance_active', __('钱包状态'));
        $show->field('blance_bak', __('冻结说明'));
        $show->field('last_ip', __('最后登录地址'));
        $show->field('last_time', __('最后登录时间'));
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
        $form = new Form(new Buser());

        $form->text('nickname', __('用户昵称'));
        $form->text('account', __('用户账号'));
        $form->password('password', __('用户密码'));
        $form->text('realname', __('真实姓名'));
        $form->mobile('phone', __('用户手机'));
        $form->text('headimg', __('头像图片'));
        $form->number('parent', __('上级ID'))->default(0);
        $form->number('group', __('用户级别'));

        $form->switch('active', __('活动状态'))->default(1);

        // MD5 保存密码
        $form->saving(function (Form $form) {
            $form->password = md5($form->password);
        });

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });
        return $form;
    }
}
