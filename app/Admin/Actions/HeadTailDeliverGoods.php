<?php

namespace App\Admin\Actions;

use Throwable;
use Encore\Admin\Admin;
use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

class HeadTailDeliverGoods extends Action
{
    protected $selector = '.head-tail-deliver-goods';

    public function handle(Request $request)
    {
        // $request ...
        try { 
            if(!$request->head or !$request->tail or !$request->user  or !$request->policy){
                return $this->response()->error('参数无效!')->refresh();
            }

            //验证首位
            if(!is_numeric($request->head) or !is_numeric($request->tail)){
                return $this->response()->error('首尾终端需为整数!')->refresh();
            }

            if($request->tail < $request->head){
                return $this->response()->error('终端尾行不能低于首行')->refresh();
            }

            if(strlen($request->head) != strlen($request->tail)){
                return $this->response()->error('终端首尾长度不一样')->refresh();
            }

            $data = [];

            //
            $lenth = strlen($request->head);


            for($i = $request->head; $i<= $request->tail; $i++){

                $i =sprintf("%0".$lenth."d", $i);

                $data[] = $i;

            }


            \App\Merchant::whereIn('merchant_terminal', $data)->where('bind_status', '0')->update([
                'user_id'   =>  $request->user,
                'policy_id' =>  $request->policy
            ]);

            return $this->response()->success('发货成功')->refresh();

        }catch (Throwable $throwable) {

            $this->response()->status = false;

            return $this->response()->swal()->error($throwable->getMessage());
        }

        return $this->response()->success('发货成功!')->refresh();
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default head-tail-deliver-goods" style="position:absolute;  right: 250px;"><i class="fa fa-balance-scale" style="margin-right: 3px;"></i>首尾发货</a>
HTML;
    }


    /**
     * [form 点击的按钮 出来的表单]
     * @author Pudding
     * @DateTime 2020-04-21T15:58:56+0800
     * @return   [type]                   [description]
     */
    public function form()
    {
        $user = \App\Buser::pluck('nickname as name','id');
        $this->select('user', '配送会员')->options($user)->rules('required', ['required' => '请选择品牌']);


        $policy = \App\Policy::where('active', '1')->pluck('title as name','id');
        $this->select('policy', '政策活动')->options($policy)->rules('required', ['required' => '请选择品牌']);

        $this->text('head', '机具首行终端号')->rules('required', ['required' => '首行不能为空']);

        $this->text('tail', '机具尾行终端号')->rules('required', ['required' => '尾行不能为空']);
    }
}