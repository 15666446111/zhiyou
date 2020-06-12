<?php

namespace App\Jobs;

use App\Trade;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TradeHandle implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * [$trade trade表交易记录的单条数据模型]
     * @var [type]
     */
    protected $trade;

    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * $this->trade 为任务实例
     * 
     * @return void
     */
    public function __construct(Trade $trade)
    {
        $this->trade = $trade;
    }

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 1;

    /**
     * 任务执行的主任务。负责分发其他任务
     *
     * 应用逻辑判断 以及流程分发
     *
     * @return void
     */
    public function handle()
    {
        /**
         * @version [< 首先要判断交易状态。如果交易状态为失败 则不进行处理>]
         * [<description>]
         */
        if($this->trade->trade_status == '-1'){
            $this->trade->remark = '交易不成功,不进行分润/激活/返现处理!';
            $this->trade->save();
            return false;
        }

        /**
         * @version [< 判断当前终端号所对应的机器是否绑定 >] [<description>]
         * 如果这时候机器没有绑定 ， 先去填写商户资料进行绑定机器
         */
        if(!$this->trade->merchants_sn or empty($this->trade->merchants_sn)){
            $this->trade->remark = '仓库中无此终端号!';
            $this->trade->save();
            return false;
        }

        /**
         * @version [<vector>] [< 检查该机器是否有活动政策并发货>]
         */
        if(!$this->trade->merchants_sn->user_id or $this->trade->merchants_sn->user_id == "null"){
            $this->trade->remark = '该机器还未发货!';
            $this->trade->save();
            return false;
        }

        /**
         * @version [<vector>] [< 检查该机器是否有活动政策并发货>]
         */
        if(!$this->trade->merchants_sn->policy_id or $this->trade->merchants_sn->policy_id == "0"){
            $this->trade->remark = '该机器还未配置活动政策!';
            $this->trade->save();
            return false;
        }


        /**
         * [$this->trade->merchants->bind_status description]
         * @var [type]
         */
        if($this->trade->merchants_sn->bind_status == "0" || $this->trade->merchants_sn->merchant_number == ""){
            // 执行商户绑定
            $bind = new \App\Http\Controllers\BindMerchantController();

            $bind->bind($this->trade);
        }


        /**
         * [如果是激活交易。 则需要给机器做激活返现]
         * @var [type]
         */
        if($this->trade->trade_type == 'VIPPAY'){

            try{

                $active = new \App\Http\Controllers\ActiveMerchantController($this->trade);

                $activeResult = $active->active();

                $this->trade->remark = $this->trade->remark."<br/>激活:".$activeResult['message'];

                $this->trade->save();

            } catch (\Exception $e) {

                $this->trade->remark = $this->trade->remark."<br/>激活:".json_encode($e->getMessage());

                $this->trade->save();

            }
        }

        /**
         * @version [< 给当前交易进行分润发放 >]
         */
        try{
            $cash = new \App\Http\Controllers\CashMerchantController($this->trade);

            $cashResult = $cash->cash();

            $this->trade->remark = $this->trade->remark."<br/>分润:".$cashResult['message'];

            if($cashResult['status'] && $cashResult['status'] !== false){
                $this->trade->is_cash = 1;
            }

            $this->trade->save();

        } catch (\Exception $e) {
            $this->trade->remark = $this->trade->remark."<br/>分润:".json_encode($e->getMessage());
            $this->trade->save();
        }


        /**
         * @version [< 达标返现 或者累积达标返现 >] [<description>]
         */
        // 如果达标状态为连续达标中 正常情况下 去执行达标返现政策
        if($this->trade->merchants_sn->standard_statis != "-1")
        {
             try{
                $standard = new \App\Http\Controllers\StandardMerchantController($this->trade);

                $standardResult = $standard->standard();

                $this->trade->remark = $this->trade->remark."<br/>分润:".$cashResult['message'];

                if($cashResult['status'] && $cashResult['status'] !== false){
                    $this->trade->is_cash = 1;
                }

                $this->trade->save();

            } catch (\Exception $e) {
                $this->trade->remark = $this->trade->remark."<br/>分润:".json_encode($e->getMessage());
                $this->trade->save();
            }
        }


        /**
         * @version [< 累积达标返现>] [<description>]
         */
    }
}
