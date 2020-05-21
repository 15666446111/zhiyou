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

        $grid->column('count', __('推荐多少有效用户升级'))->label();

        $grid->column('buy_count', __('采购多少台机器升级'))->label();

        $grid->column('created_at', __('创建时间'));

        $grid->actions(function ($actions) {
            // 去掉删除
            $actions->disableDelete();
            $actions->disableView();
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
        $show->field('count', __('推荐多少有效用户升级'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('修改时间'));

        $show->users('用户信息', function ($users) {
            $users->nickname('用户昵称');
            $users->created_at();
            $users->updated_at();
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

        $form->number('count',  __('推荐多少有效用户升级'))->default(0);

        $form->number('buy_count',  __('采购多少台机器升级'))->default(0);

        $form->table('standard', '达标奖励设置',function ($table) {
            $table->text('title', '达标标题');
            $table->number('start', '起(天数)')->default(0);
            $table->number('end', '止(天数)')->default(0);
            $table->number('trade', '交易金额(单位：分)')->default(0);
            $table->number('money', '奖励金额（单位：分）')->default(0);
            $table->switch('open', '开启？');
        });

        $form->table('standard_count', '累计达标返现奖励设置',function ($table) {
            $table->text('title', '累计达标标题');
            $table->number('start', '起(天数)')->default(0);
            $table->number('end', '止(天数)')->default(0);
            $table->number('trade', '交易金额(单位：分)')->default(0);
            $table->number('money', '奖励金额（单位：分）')->default(0);
            $table->switch('open', '开启？');
        });


        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
            $tools->disableView();
        });
        return $form;
    }
}
