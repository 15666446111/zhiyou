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
        $grid->column('nickname', __('用户昵称'));
        $grid->column('account', __('登录账号'));
        $grid->column('realname', __('真实姓名'));
        $grid->column('phone', __('手机号码'));
        $grid->column('headimg', __('头像图片'));
        $grid->column('parent', __('上级会员'));
        $grid->column('group', __('用户级别'));
        $grid->column('blance', __('用户余额'));
        $grid->column('score', __('用户积分'));
        $grid->column('active', __('活动状态'));
        $grid->column('blance_active', __('钱包状态'));
        $grid->column('last_ip', __('最后登录IP'));
        $grid->column('last_time', __('最后登录时间'));
        $grid->column('created_at', __('注册时间'));
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

        $show->field('id', __('Id'));
        $show->field('nickname', __('Nickname'));
        $show->field('account', __('Account'));
        $show->field('password', __('Password'));
        $show->field('realname', __('Realname'));
        $show->field('phone', __('Phone'));
        $show->field('headimg', __('Headimg'));
        $show->field('parent', __('Parent'));
        $show->field('group', __('Group'));
        $show->field('blance', __('Blance'));
        $show->field('score', __('Score'));
        $show->field('active', __('Active'));
        $show->field('blance_active', __('Blance active'));
        $show->field('blance_bak', __('Blance bak'));
        $show->field('last_ip', __('Last ip'));
        $show->field('last_time', __('Last time'));
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
        $form = new Form(new Buser());

        $form->text('nickname', __('用户昵称'));
        $form->text('account', __('用户账号'));
        $form->password('password', __('用户密码'));
        $form->text('realname', __('真实姓名'));
        $form->mobile('phone', __('用户手机'));
        $form->text('headimg', __('头像图片'));
        $form->number('parent', __('上级ID'));
        $form->number('group', __('用户级别'));
        $form->number('blance', __('用户余额'))->default(0);
        $form->number('score', __('用户积分'))->default(0);
        $form->switch('active', __('活动状态'))->default(1);
        $form->switch('blance_active', __('钱包状态'))->default(1);
        $form->text('blance_bak', __('冻结说明'));
        //$form->text('last_ip', __('Last ip'));
        //$form->datetime('last_time', __('Last time'))->default(date('Y-m-d H:i:s'));
        return $form;
    }
}
