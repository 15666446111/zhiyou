<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

class MachineHeadTail extends Action
{
    protected $selector = '.machine-head-tail';

    public function handle(Request $request)
    {
        // $request ...
        try { 
            if(!$request->head or !$request->tail){
                return $this->response()->error('参数无效!')->refresh();
            }


        }catch (Throwable $throwable) {

            $this->response()->status = false;

            return $this->response()->swal()->error($throwable->getMessage());
        }

        return $this->response()->success('补全成功!')->refresh();
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default machine-head-tail"><i class="fa fa-balance-scale" style="margin-right: 3px;"></i>首尾补全</a>
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
        $Brand = \App\Brand::where('active', '1')->pluck('brand_name as name','id');

        $this->select('brand', '机具品牌')->options($Brand);

        $this->text('head', '机具首行终端号')->rules('required', ['required' => '首行不能为空']);

        $this->text('tail', '机具尾行终端号')->rules('required', ['required' => '尾行不能为空']);
    }
}