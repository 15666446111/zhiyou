<?php

namespace App\Admin\Actions;

use Illuminate\Http\Request;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class DeliverGoods extends RowAction
{
    public $name = '发货';

    public function handle(Model $model, Request $request)
    {
        // $request ...
        try { 

            if(!$request->user or !$request->policy){
                return $this->response()->error('参数无效!')->refresh();
            }

            $model->user_id = $request->user;

            $model->policy_id = $request->policy;

            $model->save();

            /*
            	检查该会员在该政策下是否有结算激活等配置。如果没有 进行默认该政策配置
             */
            $userPolicy  = \App\UserPolicy::where('user_id', $request->user)->where('policy_id', $request->policy)->first();

            if(!$userPolicy or empty($userPolicy)){

            	$policy = \App\Policy::where('id', $request->policy)->first();

            	\App\UserPolicy::create([
            		'user_id'		=>	$request->user,
            		'policy_id'		=>	$request->policy,
            		'sett_price'	=>	$policy->sett_price
            	]);
            }

            return $this->response()->success('发货成功')->refresh();

        }catch (Throwable $throwable) {

            $this->response()->status = false;

            return $this->response()->swal()->error($throwable->getMessage());
        }

    }

    /* 发货按钮需要提交资料 */
	public function form()
	{
		$user = \App\Buser::pluck('nickname as name','id');
		$this->select('user', '配送会员')->options($user)->rules('required', ['required' => '请选择品牌']);


		$policy = \App\Policy::where('active', '1')->pluck('title as name','id');
		$this->select('policy', '政策活动')->options($policy)->rules('required', ['required' => '请选择品牌']);
	}
}