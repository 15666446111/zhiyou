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

        $grid->model()->latest();

        $grid->column('id', __('索引'))->sortable();
        $grid->column('nickname', __('昵称'));
        $grid->column('account', __('账号'));
        $grid->column('realname', __('姓名'));
        $grid->column('phone', __('手机号'));
        $grid->column('headimg', __('头像图片'));
        $grid->column('groups.name', __('级别'))->label();
        $grid->column('wallets.cash_blance', __('分润余额'))->display(function ($money) {
            return number_format($money/100, 2, '.', ',');
        })->label('info');
        $grid->column('wallets.return_blance', __('返现余额'))->display(function ($money) {
            return number_format($money/100, 2, '.', ',');
        })->label('warning');
        $grid->column('wallets.blance_active', __('钱包'))->bool();
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

        $show->field('active', __('用户状态'));

        $show->field('last_ip', __('最后登录地址'));

        $show->field('last_time', __('最后登录时间'));

        $show->field('created_at', __('创建时间'));

        $show->field('updated_at', __('修改时间'));


        $show->merchants('机具详情', function ($merchants) {

            $merchants->setResource('/admin/merchants');
            
            //$merchants->model()->latest();
            
            $merchants->id('索引')->sortable();

            $merchants->column('merchant_terminal', __('终端编号'));

            $merchants->column('brands.brand_name', __('终端品牌'));

            $merchants->column('policys.title', __('政策活动'));

            $merchants->column('merchant_number', __('商户编号'));

            $merchants->column('merchant_name', __('商户名称'));

            $merchants->column('user_phone', __('电话号码'));

            $merchants->column('bind_status', __('绑定'))->bool();

            $merchants->column('bind_time', __('绑定时间'))->sortable();

            $merchants->column('active_status', __('激活'))->bool();

            $merchants->column('active_time', __('激活时间'))->sortable();

            $merchants->created_at('创建时间')->date('Y-m-d H:i:s');

            $merchants->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                $filter->column(1/3, function ($filter) {
                    $filter->like('merchant_sn', 'sn');
                });

                $filter->column(1/3, function ($filter) {
                    $filter->like('merchant_id', '商户');
                });

                $filter->column(1/3, function ($filter) {
                    $filter->equal('bind_status', '商户名称')->select(['0' => '未绑定', '1' => '已绑定']);
                });

            });

            $merchants->disableCreateButton();
            $merchants->actions(function ($actions) {
                // 去掉删除 编辑
                $actions->disableDelete();
                $actions->disableEdit();
            });
            $merchants->batchActions(function ($batch) {
                $batch->disableDelete();
            });

        });

        // 分润返现
        $show->cashs('分润返现', function ($cashs) {

            $cashs->setResource('/admin/cashes');

            $cashs->model()->latest();
            
            $cashs->id('索引')->sortable();
            $cashs->column('order', __('分润订单'));
            $cashs->column('users.nickname', __('分润会员'));
            $cashs->column('users.account', __('会员账号'));
            $cashs->column('cash_money', __('分润金额'))->display(function ($money) {
                return number_format($money / 100, 2, '.', ',');
            })->label();
            $cashs->column('cash_type', __('分润类型'))->using([
                '1' => '直营分润', '2' => '团队分润' , '3' => '直推分润' , '4' => '间推分润' ,  
                '5' => '激活返现', '6' => '直推激活' , '7' => '间推激活' , '8' => '团队激活'
            ]);
            $cashs->column('status', __('分润状态'))->bool();
            $cashs->column('remark', __('分润备注'));
            $cashs->column('created_at', __('分润时间'));

            $cashs->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                $filter->column(1/3, function ($filter) {
                    $filter->like('order', '订单');
                });
                
                $filter->column(1/3, function ($filter) {
                    $filter->equal('status', '状态')->select(['0' => '失败', '1' => '成功']);
                });

                $filter->column(1/3, function ($filter) {
                    $filter->equal('status', '类型')->select([
                        '1' => '直营分润', '2' => '团队分润' , '3' => '直推分润' , '4' => '间推分润' ,  
                        '5' => '激活返现', '6' => '直推激活' , '7' => '间推激活' , '8' => '团队激活'
                    ]);
                });

            });

            $cashs->disableCreateButton();
            $cashs->actions(function ($actions) {
                // 去掉删除 编辑
                $actions->disableDelete();
                $actions->disableEdit();
            });
            $cashs->batchActions(function ($batch) {
                $batch->disableDelete();
            });
        
        });

        $show->messages('消息通知', function ($messages) {

            $messages->setResource('/admin/buser-messages');

            $messages->model()->latest();
            
            $messages->id('索引')->sortable();
            $messages->column('type', __('类型'));
            $messages->column('is_read', __('已读'))->bool();
            $messages->column('title', __('标题'));
            $messages->column('send_plat', __('发送方'));
            $messages->column('created_at', __('发送时间'));

            $messages->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                $filter->column(1/3, function ($filter) {
                    $filter->like('title', '标题');
                });
                
                $filter->column(1/3, function ($filter) {
                    $filter->equal('type', '类型')->select(['0' => '失败', '1' => '成功']);
                });

            });

            $messages->disableCreateButton();
            $messages->actions(function ($actions) {
                // 去掉删除 编辑
                $actions->disableDelete();
                $actions->disableEdit();
            });
            $messages->batchActions(function ($batch) {
                $batch->disableDelete();
            });
        
        });


        $show->banks('结算卡信息', function ($banks) {

            //$banks->setResource('/admin/buser-messages');

            $banks->model()->latest();
            
            $banks->id('索引')->sortable();
            $banks->column('name', __('持卡人'));
            $banks->column('bank_name', __('银行'));
            $banks->column('bank', __('银行卡号'));
            $banks->column('number', __('身份证号'));
            $banks->column('open_bank', __('开户行'));

            $banks->column('is_default', __('是否默认'))->bool();

            $banks->column('is_del', __('是否删除'))->bool();
            
            $banks->column('created_at', __('创建时间'));

            $banks->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                $filter->column(1/3, function ($filter) {
                    $filter->like('name', '持卡人');
                });

                $filter->column(1/3, function ($filter) {
                    $filter->like('number', '身份证号');
                });

                $filter->column(1/3, function ($filter) {
                    $filter->like('bank', '银行卡号');
                });

            });

            $banks->disableCreateButton();
            $banks->actions(function ($actions) {
                // 去掉删除 编辑
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->disableView();
            });
            $banks->batchActions(function ($batch) {
                $batch->disableDelete();
            });
        
        });



        $show->panel()->tools(function ($tools) {
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
        $form = new Form(new Buser());

        $form->text('nickname', __('用户昵称'));
        $form->text('account', __('用户账号'));
        $form->password('password', __('用户密码'));
        $form->text('realname', __('真实姓名'));
        $form->mobile('phone', __('用户手机'));
        $form->image('headimg', __('头像图片'));

        $form->number('parent', __('上级ID'))->default(0);
        
        $form->select('group', __('用户级别'))->options(\App\UserGroup::get()->pluck('name', 'id'));

        $form->switch('active', __('活动状态'))->default(1);

        // MD5 保存密码
        $form->saving(function (Form $form) {
            if($form->isCreating()){
                $form->password = md5($form->password);
            }else{
                if($form->model()->password != $form->password){
                    $form->password = md5($form->password);
                }
            }
        });

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });
        return $form;
    }
}
