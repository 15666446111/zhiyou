<?php

namespace App\Admin\Controllers;

use App\BuserMessage;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BuserMessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\BuserMessage';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BuserMessage());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('type', __('Type'));
        $grid->column('is_read', __('Is read'));
        $grid->column('title', __('Title'));
        $grid->column('message_text', __('Message text'));
        $grid->column('send_plat', __('Send plat'));
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
        $show = new Show(BuserMessage::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('type', __('Type'));
        $show->field('is_read', __('Is read'));
        $show->field('title', __('Title'));
        $show->field('message_text', __('Message text'));
        $show->field('send_plat', __('Send plat'));
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
        $form = new Form(new BuserMessage());

        $form->number('user_id', __('User id'));
        $form->text('type', __('Type'))->default('other');
        $form->switch('is_read', __('Is read'));
        $form->text('title', __('Title'));
        $form->textarea('message_text', __('Message text'));
        $form->text('send_plat', __('Send plat'))->default('系统发送');

        return $form;
    }
}
