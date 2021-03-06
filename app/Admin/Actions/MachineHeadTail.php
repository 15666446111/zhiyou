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

            //验证首位
            if(!is_numeric($request->f_head) or !is_numeric($request->f_tail)){
                return $this->response()->error('首尾终端需为整数!')->refresh();
            }

            if($request->f_tail < $request->f_head){
                return $this->response()->error('终端尾行不能低于首行')->refresh();
            }

            $data = [];

            if(strlen($request->f_head) != strlen($request->f_tail)){
                return $this->response()->error('终端首尾长度不一样')->refresh();
            }

            //
            $lenth = strlen($request->f_head);


            for($i = $request->f_head; $i<= $request->f_tail; $i++){

                $i =sprintf("%0".$lenth."d", $i);

                $data[] = $i;

            }

            $eplice = \App\Merchant::whereIn('merchant_terminal', $data)->pluck('merchant_terminal')->toArray();
            // 交集
            $epliceRows = array_intersect($data, $eplice);
            // 差集
            $InsertData = array_diff($data, $eplice);

            foreach ($InsertData as $key => $value) {
                \App\Merchant::create([
                    'merchant_sn'       =>  $value,
                    'brand_id'          =>  $request->f_brand,
                ]);
            }

            return $this->response()->success('入库成功, 入库'.count($InsertData).'台!')->refresh();

        }catch (Throwable $throwable) {

            $this->response()->status = false;

            return $this->response()->swal()->error($throwable->getMessage());
        }

        return $this->response()->success('补全成功!')->refresh();
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default machine-head-tail" style="position:absolute;  right: 250px;"><i class="fa fa-balance-scale" style="margin-right: 3px;"></i>首尾补全</a>
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

        $this->select('f_brand', '机具品牌')->options($Brand)->rules('required', ['required' => '请选择品牌']);

        $this->text('f_head', '机具首行终端sn')->rules('required', ['required' => '首行不能为空']);

        $this->text('f_tail', '机具尾行终端sn')->rules('required', ['required' => '尾行不能为空']);
    }
}