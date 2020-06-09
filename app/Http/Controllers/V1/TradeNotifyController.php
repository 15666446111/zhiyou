<?php

namespace App\Http\Controllers\V1;

use App\Jobs\TradeHandle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TradeNotifyController extends Controller
{
    /*
    	本文件为汇付数据交易通知接口
    	如非必要 请勿修改
    	@version Pudding
    	@email   755969423@qq.com
    	@data    2020-05-22
    */
   	public function trade(Request $request)
   	{


   		// 接受请求数据
   		$params = $request->input();

   		// 写入到推送信息
   		$trade_push = \App\TradeNotify::create([
   			'title'		=>	'汇付交易接口',
   			'content'	=>	$params['jsonData']."_".$params['checkValue'],
   			'other'		=>	json_encode(['请求方式'=>$request->getMethod(), '请求地址'=>$request->ip(), '端口'=> $request->getPort(), '请求头' => $request->header('Connection')]),
   		]);

   		// 如果没有包含这两个值。则结束掉程序运行
   		// 因为汇付接口传递过来的只有这两个参数 且必填
   		if(!isset($params['jsonData']) and !isset($params['checkValue'])) return response()->json(['error'=>['message' => '请求出错!']]);

   		// 前去签名验证 验证签名是否符合


      // 交易数据详情 请求报文中的请求订单详情
      $response = json_decode($params['jsonData']);

      // 新建交易订单 写入交易表 并且 分发到队列处理
      $tradeOrder = \App\Trade::create([

        // trade_notify 的记录ID
        'notify_id'     =>  $trade_push->id,

        // 交易订单编号。
        'order'         =>  $response->logNo,

        // 交易批次号。如有 请上传 没有可不理
        'batch_no'      =>  $response->batNo,

        // 终端流水号 如有 请上传。没有可不理
        'terminal_no'   =>  $response->cseqNo,

        // 终端号。必填 *  机器终端号
        'terminal'      =>  $response->trmNo,

        // 商户id
        'merchant_id'   =>  $response->mercId,

        // 渠道商户号
        'agt_merchant_id' =>  $response->agtMercId,

        // 渠道商户名称
        'agt_merchant_name'=> $response->agtMercNm,

        //
        'agt_merchant_level'=>  $response->agtMercLvl,

        // 商户SN号
        'merchant_sn'     =>  $response->snNo,

        // 商户编号名称
        'merchant_name'   =>  $response->mercNm,

        // 交易金额 此项必须填写 用于计算分润。单位为分
        'money'           =>  $response->txnAmt,

        // 交易手续费。此项尽量填写 单位为分,
        'rate_money'      =>  $response->mercFeeAmt,

        // 手续费类型 。此项尽量填写 1-非封顶  2-封顶
        'fee_type'        =>  $response->feeTyp,  

        // 交易卡类型 必填。此处计算分润使用 借记卡 贷记卡  准贷记卡  预付费卡
        'card_type'       =>  $response->crdFlg,

        // 交易卡号 如有 请上传
        'card_number'     =>  $response->crdNo,

        // 交易类型 必填 请上传 分润需根据此字段计算
        // CLOUDPAY:云闪付
        // SMALLFREEPAY:小额双免
        // VIPPAY:激活交易
        // CARDPAYRVS:消费冲正
        // CARDPAY:刷卡消费
        // CARDCANCEL:消费撤销
        // QUICKPAY:快捷支付
        // WXQRPAY:微信扫码
        // ALIQRPAY:支付宝扫码
        // UNIONQRPAY:银联扫码
        // CARDAUTH:预授权
        // CARDCANCELAUTH:预授权撤销
        // CARDAUTHED:预授权完成
        // CARDCANCELAUTHED:预授权完成撤销
        'trade_type'      => $response->txnCd,

        //  收款类型 如有 请上传 1：vip收款  2：自主收款  4：智能路由  0：普通收款
        'collection_type' => $response->memberCou,

        //  结算金额 必填 请上传。 单位为分
        'real_money'      => $response->stlAmt,

        // 交易状态 必填 请上传。1成功  2 冲正  -1 失败  3结算中
        'trade_status'    => $response->ttxnSts == 's' ? 1 : ($response->ttxnSts == 'c' ? 2 : -1),

        // 清算状态 如有 请上传  0：未清算；1：已清算；
        'audit_status'    => $response->stlSts,

        // 流量卡费 如有 请上传  0 正常交易 1 全扣 2 内扣
        'is_sim'          => $response->isSim,

        // 结算标示 如有 请上传  0:TS, 1:T1
        'stl_type'        => $response->stlTyp,

        // 正反扫标识 如有 请上传  POSITIVE：正扫  NEGATIVE：反扫
        'scan_flag'       => $response->scanFlag,

        // 调价 如有 请上传 0：上调  1：下调  2和null：不调
        'clr_flag'        =>  $response->clrFlg,

        // 是否本人卡 如有 请上传 是否认证信用卡 0：否 1：他人认证信用卡 2：本人认证信用卡 其他为否
        'is_auth_credit_card' =>  $response->isAuthCreditCard,

        // 交易时间 如有 请上传  yyyyMMddHHmmss
        'trade_time'          =>  $response->txnTm,

        // 交易接收时间 如有 请上传 yyyyMMdd
        'trade_actime'        =>  $response->acDt
      ]);

      // dd($tradeOrder);
      // 分发到队列 由队列去处理剩下的逻辑
      // 分发到队列的任务为交易表的数据信息 (trade表的交易ID)
      TradeHandle::dispatch($tradeOrder);

      $trade_push->is_queue = 1;

      $trade_push->save(); 

      die("RECV_ORD_ID_".$tradeOrder->order);

    }
      



  public function jj(Request $request)
  {

  }
}
