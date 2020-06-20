<?php

namespace App\Admin\Controllers;

use App\ArticleType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ArticleTypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '文章类型';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ArticleType());

        $grid->model()->latest();

        $grid->column('id', __('索引'))->sortable();

        $grid->column('name', __('类型'));

        $grid->column('active', __('状态'))->sortable()->switch();

        $grid->column('created_at', __('创建时间'))->date('Y-m-d H:i:s');

        $grid->column('updated_at', __('修改时间'))->date('Y-m-d H:i:s');


        $grid->filter(function($filters){
            // 去掉默认的id过滤器
            $filters->disableIdFilter();

            $filters->column(1/4, function ($filter) {
                $filter->like('name', '类型');
            });

            // 在这里添加字段过滤器 
        });

        $grid->disableCreateButton();

        $grid->actions(function ($actions) {
            if($actions->getKey() <= 3){
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
        $show = new Show(ArticleType::findOrFail($id));

        $show->field('name', __('类型'));

        $show->field('active', __('状态'))->using([0 => '关闭', 1 => '开启'])->label('info');

        $show->field('created_at', __('创建时间'));

        $show->field('updated_at', __('修改时间'));

        $show->articles('文章列表', function ($articles) {

            $articles->setResource('/admin/articles');
            
            $articles->model()->latest();
            
            $articles->id('索引')->sortable();

            $articles->title('标题');

            $articles->images('图片')->image('', 100, 30);

            $articles->active('状态')->bool()->sortable();

            $articles->created_at('创建时间')->date('Y-m-d H:i:s');

            $articles->filter(function($filter){
                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                $filter->column(1/4, function ($filter) {
                    $filter->like('title', '标题');
                });
                $filter->column(1/4, function ($filter) {
                    $filter->equal('active', '状态')->select(['0' => '关闭', '1' => '开启']);
                });
                // 在这里添加字段过滤器 
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
        $form = new Form(new ArticleType());

        $form->text('name', __('类型'));

        $form->switch('active', __('开启'))->default(1);

        return $form;
    }
}
