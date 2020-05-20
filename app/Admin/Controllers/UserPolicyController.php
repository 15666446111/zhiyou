<?php

namespace App\Admin\Controllers;

use App\UserPolicy;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserPolicyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\UserPolicy';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserPolicy());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('policy_id', __('Policy id'));
        $grid->column('sett_price', __('Sett price'));
        $grid->column('active_return', __('Active return'));
        $grid->column('standard', __('Standard'));
        $grid->column('standard_count', __('Standard count'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(UserPolicy::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('policy_id', __('Policy id'));
        $show->field('sett_price', __('Sett price'));
        $show->field('active_return', __('Active return'));
        $show->field('standard', __('Standard'));
        $show->field('standard_count', __('Standard count'));
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
        $form = new Form(new UserPolicy());

        $form->text('user_id', __('User id'));
        $form->text('policy_id', __('Policy id'));
        $form->number('sett_price', __('Sett price'));
        $form->textarea('active_return', __('Active return'));
        $form->textarea('standard', __('Standard'));
        $form->textarea('standard_count', __('Standard count'));

        return $form;
    }
}
