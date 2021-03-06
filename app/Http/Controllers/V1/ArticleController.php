<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ArticleController extends Controller
{	
	/**
	 * @version  [<获取系统公告列表>]
	 * @author   Pudding   
	 * @DateTime 2020-04-08T17:17:40+0800
	 * @param    Request
	 * @return   [type]
	 */
    public function Notice(Request $request)
    {
    	try{
            // 获取展示的轮播图
            $Article = \App\Article::where('active', '1')->where('type_id', '1')->orderBy('id', 'desc')->first();

            return response()->json(['success'=>['message' => '获取成功!', 'data' => $Article]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @version  [<获取常见问题列表>]
     * @author   Pudding   
     * @DateTime 2020-04-08T17:17:40+0800
     * @param    Request
     * @return   [type]
     */
    public function problem(Request $request)
    {
        try{
            // 获取展示的轮播图
            $Article = \App\Article::where('active', '1')->where('type_id', '2')->orderBy('id', 'desc')->offset(0)->limit(5)->get();

            return response()->json(['success'=>['message' => '获取成功!', 'data' => $Article]]);

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @version  [<获取微信分享文案列表>]
     * @author   Pudding   
     * @DateTime 2020-04-08T17:17:40+0800
     * @param    Request
     * @return   [type]
     */
    public function wxShare(Request $request)
    {
        try{
            // 获取展示的文案
            $Article = \App\Article::where('active', '1')->where('type_id', '3')->orderBy('id', 'desc')->offset(0)->limit(5)->get();

            return response()->json(['success'=>['message' => '获取成功!', 'data' => $Article]]);

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }

    /**
     * 获取文章详情接口
     */
    public function Article(Request $request)
    {

        try{
            
            $data = \App\Article::where('active', '1')->where('id', $request->aid)->first();

            if(!$data or empty($data))
                return response()->json(['success'=>['message' => '暂无数据!', 'data' => []]]);

            return response()->json(['success'=>['message' => '获取成功!', 'data' => $data]]);

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }

    }
}
