<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgentController extends Controller
{
    /**
     * @Author    Pudding
     * @DateTime  2020-06-06
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取代理的商户分布情况。返回代理个人商户 与 代理商户情况]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function getAgentDetail(Request $request)
    {
        if(!$request->uid) return response()->json(['error'=>['message' => '无效参数']]);

        $arrs = array();

        // 获取代理
        $team = \App\BuserParent::where('parents', 'like', '%_'.$request->uid.'_%')->pluck('user_id')->toArray();

        $arrs['agent']  = \App\Merchant::whereIn('user_id', $team)->count();

        // 获取个人商户情况
        $arrs['me'] = \App\Merchant::where('user_id', $request->uid)->count();

        return response()->json(['success'=>['message' => '获取成功!', 'data'=>$arrs]]);

    }


    /**
     * @Author    Pudding
     * @DateTime  2020-06-06
     * @copyright [copyright]
     * @license   [license]
     * @version   [获取某个代理的团队发展情况]
     * @param     Request     $request [description]
     * @return    [type]               [description]
     */
    public function getAgentTeamDetail(Request $request)
    {
        if(!$request->uid) return response()->json(['error'=>['message' => '无效参数']]);

        $arrs = array();

        $arrs['me'] = \App\Buser::where('parent', $request->uid)->count();

        $arrs['agent'] = \App\BuserParent::where('parents', 'like', '%_'.$request->uid.'_%')->count() - $arrs['me'];

        return response()->json(['success'=>['message' => '获取成功!', 'data'=>$arrs]]);
    }
}
