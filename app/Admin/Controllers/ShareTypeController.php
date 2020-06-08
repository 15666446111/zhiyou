<?php

namespace App\Admin\Controllers;

use App\ShareType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ShareTypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '分享类型管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ShareType());

        $grid->column('id', __('索引'));
        $grid->column('name', __('类型'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('修改时间'));
        

        $grid->actions(function ($actions) {
            if($actions->getKey() <= 4){
                $actions->disableDelete();
                $actions->disableEdit();
            }
        });
        
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
        $show = new Show(ShareType::findOrFail($id));

        $show->field('name', __('分享类型'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('修改时间'));

        $show->shares('素材列表', function ($shares) {

            $shares->setResource('/admin/shares');
            
            $shares->model()->latest();
            
            $shares->id('索引')->sortable();

            $shares->title('标题');

            $shares->share_text('分享文案');

            $shares->image('图片')->image('', 100, 30);

            $shares->active('状态')->bool()->sortable();

            $shares->created_at('创建时间')->date('Y-m-d H:i:s');

            $shares->filter(function ($filter) {
                $filter->like('title', '标题');
            });
        });


        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
            $tools->disableDelete();
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
        $form = new Form(new ShareType());

        $form->text('name', __('分享类型'));

        return $form;
    }
}
