<?php

namespace App\Admin\Controllers;

use App\Article;
use App\ArticleType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ArticleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '文章列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article());

        $grid->model()->latest();

        $grid->column('id', __('索引'))->sortable();
        //
        $grid->column('title', __('标题'));

        $grid->column('active', __('状态'))->switch()->sortable();

        $grid->column('images', __('图片'))->image('', 100, 30);

        $grid->column('article_types.name', __('文章类型'));

        $grid->column('created_at', __('创建时间'))->date('Y-m-d H:i:s');

        //$grid->column('updated_at', __('Updated at'));
        //
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->column(1/4, function ($filter) {
                $filter->like('title', '标题');
            });
            // 在这里添加字段过滤器
            
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
        $show = new Show(Article::findOrFail($id));

        $show->field('title', __('标题'));

        $show->field('active', __('状态'))->using([0 => '关闭', 1 => '开启'])->label('info');

        $show->field('images', __('图片'))->image();

        $show->field('created_at', __('创建时间'));

        $show->field('updated_at', __('修改时间'));

        $show->content('文章内容')->unescape()->as(function ($content) {
            return $content;
        });

        $show->article_types('分类信息', function ($type) {
            $type->name('类型名称');
            $type->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });
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
        $form = new Form(new Article());

        $form->text('title', __('标题'));

        $form->switch('active', __('状态'))->default(1);

        $form->image('images', __('图片'));

        $form->select('type_id', __('类型'))->options(ArticleType::where('active', '1')->get()->pluck('name', 'id'));

        $form->select('verify', __('审核'))->options([
            0   =>  '待审核',
            1   =>  '正常',
            -1  =>  '拒绝',
        ]);

        $form->UEditor('content', __('文章内容'));

        return $form;
    }
}
