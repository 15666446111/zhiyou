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
        // $model ...

        return $this->response()->success('Success message.')->refresh();
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