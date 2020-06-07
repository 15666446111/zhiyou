<?php

namespace App\Admin\Controllers;

use App\ApplicationForm;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ApplicationFormController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商户推荐_申请列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ApplicationForm());

        $grid->column('id', __('索引'));
        $grid->column('user_id', __('会员'));
        $grid->column('agent_id', __('代理'));
        $grid->column('name', __('申请人'));
        $grid->column('phone', __('电话'));
        $grid->column('address', __('地址'));
        $grid->column('is_handle', __('是否处理'))->bool();
        $grid->column('handle_time', __('处理时间'));
        $grid->column('handle_temail', __('机器终端'));
        $grid->column('created_at', __('申请时间'));

        $grid->disableActions();

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
        $show = new Show(ApplicationForm::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('agent_id', __('Agent id'));
        $show->field('name', __('Name'));
        $show->field('phone', __('Phone'));
        $show->field('address', __('Address'));
        $show->field('is_handle', __('Is handle'));
        $show->field('handle_time', __('Handle time'));
        $show->field('handle_temail', __('Handle temail'));
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
        $form = new Form(new ApplicationForm());

        $form->number('user_id', __('User id'));
        $form->number('agent_id', __('Agent id'));
        $form->text('name', __('Name'));
        $form->mobile('phone', __('Phone'));
        $form->text('address', __('Address'));
        $form->switch('is_handle', __('Is handle'));
        $form->datetime('handle_time', __('Handle time'))->default(date('Y-m-d H:i:s'));
        $form->text('handle_temail', __('Handle temail'));

        return $form;
    }
}
