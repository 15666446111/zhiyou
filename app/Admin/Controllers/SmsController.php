<?php

namespace App\Admin\Controllers;

use App\Sms;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SmsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Sms';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Sms());

        $grid->column('id', __('Id'));
        $grid->column('phone', __('Phone'));
        $grid->column('code', __('Code'));
        $grid->column('is_use', __('Is use'));
        $grid->column('send_time', __('Send time'));
        $grid->column('out_time', __('Out time'));
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
        $show = new Show(Sms::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('phone', __('Phone'));
        $show->field('code', __('Code'));
        $show->field('is_use', __('Is use'));
        $show->field('send_time', __('Send time'));
        $show->field('out_time', __('Out time'));
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
        $form = new Form(new Sms());

        $form->mobile('phone', __('Phone'));
        $form->text('code', __('Code'));
        $form->switch('is_use', __('Is use'));
        $form->datetime('send_time', __('Send time'))->default(date('Y-m-d H:i:s'));
        $form->datetime('out_time', __('Out time'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
