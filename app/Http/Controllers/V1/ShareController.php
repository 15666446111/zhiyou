<?php

namespace App\Http\Controllers\V1;

use File;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ShareController extends Controller
{	
	/**
	 * @version  [<团队扩展二维码海报分享>]
	 * @author   Pudding   
	 * @DateTime 2020-04-08T17:17:40+0800
	 * @param    Request
	 * @return   [type]
	 */
    public function team(Request $request)
    {
    	try{
            /** 获取分享类型的素材  */
            $list = \App\Share::where('active', '1')->where('type', '2')->first();

            if(!$list or empty($list))
                return response()->json(['success'=>['message' => '获取成功!', 'data' => array()]]);

            // 生成分享地址
            $Url = env('APP_URL')."/team/".Hashids::encode($request->user->id);
            
            // 二维码地址
            $CodePath = public_path('/share/'.$request->user->id.'/qrcodes/');
            // 目录不存在则重建
            File::isDirectory($CodePath) or File::makeDirectory($CodePath, 0777, true, true);
            //二维码文件
            $CodeFile = $CodePath."qrcode.png";
            // 生成二维码
            QrCode::format('png')->size($list->code_width)->margin($list->code_margin)->generate($Url, $CodeFile);

            $typeArr=getimagesize(storage_path('app/public/'.$list->image));

            switch($typeArr['mime'])
            {
                case "image/png":
                    $BackGroud=imagecreatefrompng(storage_path('app/public/'.$list->image));
                    break;

                case "image/jpg":
                    $BackGroud=imagecreatefromjpeg(storage_path('app/public/'.$list->image));
                    break;
                case "image/jpeg":
                    $BackGroud=imagecreatefromjpeg(storage_path('app/public/'.$list->image));
                    break;

                case "image/gif":
                    $BackGroud=imagecreatefromgif(storage_path('app/public/'.$list->image));
                    break;
            }


            // 合成图片
            //$BackGroud =  imagecreatefromjpeg(storage_path('app/public/'.$list->image));
            $qrcode    =  imagecreatefrompng($CodeFile);

            imagecopyresampled($BackGroud, $qrcode, $list->pos_x, $list->pos_y, 0, 0, imagesx($qrcode), imagesy($qrcode), imagesx($qrcode), imagesy($qrcode));

            // 海报生成位置
            $PicPath   = public_path('/share/'.$request->user->id.'/team_share/');
            File::isDirectory($PicPath) or File::makeDirectory($PicPath, 0777, true, true);

            $PicFile   = $PicPath.$list->id.".png";

            imagepng($BackGroud, $PicFile);

            $link = env('APP_URL')."/share/".$request->user->id."/team_share/".$list->id.".png";

            return response()->json(['success'=>['message' => '获取成功!', 'data' => ['link' => $link."?time=".time() ]]]);

    	} catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @version  [<团队扩展 扩展用户海报>]
     * @author   Pudding   
     * @DateTime 2020-04-08T17:17:40+0800
     * @param    Request
     * @return   [type]
     */
    public function extendUser(Request $request)
    {
        try{
            /** 获取分享类型的素材  */
            $list = \App\Share::where('active', '1')->where('type', '1')->first();

            if(!$list or empty($list))
                return response()->json(['success'=>['message' => '获取成功!', 'data' => array()]]);

            // 生成分享地址
            $Url = env('APP_URL')."/extendUser/".Hashids::encode($request->user->id);
            
            // 二维码地址
            $CodePath = public_path('/share/'.$request->user->id.'/qrcodes/');
            // 目录不存在则重建
            File::isDirectory($CodePath) or File::makeDirectory($CodePath, 0777, true, true);
            //二维码文件
            $CodeFile = $CodePath."qrcode.png";
            // 生成二维码
            QrCode::format('png')->size($list->code_width)->margin($list->code_margin)->generate($Url, $CodeFile);


            $typeArr=getimagesize(storage_path('app/public/'.$list->image));

            switch($typeArr['mime'])
            {
                case "image/png":
                    $BackGroud=imagecreatefrompng(storage_path('app/public/'.$list->image));
                    break;

                case "image/jpg":
                    $BackGroud=imagecreatefromjpeg(storage_path('app/public/'.$list->image));
                    break;
                case "image/jpeg":
                    $BackGroud=imagecreatefromjpeg(storage_path('app/public/'.$list->image));
                    break;

                case "image/gif":
                    $BackGroud=imagecreatefromgif(storage_path('app/public/'.$list->image));
                    break;
            }

            //dd($typeArr['mime']);
            // 合成图片
            //$BackGroud =  imagecreatefromjpeg(storage_path('app/public/'.$list->image));


            $qrcode    =  imagecreatefrompng($CodeFile);

            imagecopyresampled($BackGroud, $qrcode, $list->pos_x, $list->pos_y, 0, 0, imagesx($qrcode), imagesy($qrcode), imagesx($qrcode), imagesy($qrcode));

            // 海报生成位置
            $PicPath   = public_path('/share/'.$request->user->id.'/user_share/');
            File::isDirectory($PicPath) or File::makeDirectory($PicPath, 0777, true, true);

            $PicFile   = $PicPath.$list->id.".png";

            imagepng($BackGroud, $PicFile);

            $link = env('APP_URL')."/share/".$request->user->id."/user_share/".$list->id.".png";

            return response()->json(['success'=>['message' => '获取成功!', 'data' => ['link' => $link."?time=".time() ]]]);

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @version  [<商户注册二维码海报分享>]
     * @author   Pudding   
     * @DateTime 2020-04-08T17:17:40+0800
     * @param    Request
     * @return   [type]
     */
    public function merchant(Request $request)
    {
        try{
            /** 获取分享类型的素材  */
            $list = \App\Share::where('active', '1')->where('type', '3')->first();

            if(!$list or empty($list))
                return response()->json(['success'=>['message' => '获取成功!', 'data' => array()]]);

            // 生成分享地址
            $Url = config("base.merchant_register");
            // 二维码地址
            $CodePath = public_path('/share/'.$request->user->id.'/qrcodes/');
            // 目录不存在则重建
            File::isDirectory($CodePath) or File::makeDirectory($CodePath, 0777, true, true);
            //二维码文件
            $CodeFile = $CodePath."qrcode.png";
            // 生成二维码
            QrCode::format('png')->size($list->code_width)->margin($list->code_margin)->generate($Url, $CodeFile);

            $typeArr=getimagesize(storage_path('app/public/'.$list->image));

            switch($typeArr['mime'])
            {
                case "image/png":
                    $BackGroud=imagecreatefrompng(storage_path('app/public/'.$list->image));
                    break;

                case "image/jpg":
                    $BackGroud=imagecreatefromjpeg(storage_path('app/public/'.$list->image));
                    break;
                case "image/jpeg":
                    $BackGroud=imagecreatefromjpeg(storage_path('app/public/'.$list->image));
                    break;

                case "image/gif":
                    $BackGroud=imagecreatefromgif(storage_path('app/public/'.$list->image));
                    break;
            }

            // 合成图片
            //$BackGroud =  imagecreatefromjpeg(storage_path('app/public/'.$list->image));
            $qrcode    =  imagecreatefrompng($CodeFile);

            imagecopyresampled($BackGroud, $qrcode, $list->pos_x, $list->pos_y, 0, 0, imagesx($qrcode), imagesy($qrcode), imagesx($qrcode), imagesy($qrcode));

            // 海报生成位置
            $PicPath   = public_path('/share/'.$request->user->id.'/merchant_share/');
            File::isDirectory($PicPath) or File::makeDirectory($PicPath, 0777, true, true);

            $PicFile   = $PicPath.$list->id.".png";

            imagepng($BackGroud, $PicFile);

            $link = env('APP_URL')."/share/".$request->user->id."/merchant_share/".$list->id.".png";

            return response()->json(['success'=>['message' => '获取成功!', 'data' => ['link' => $link."?time=".time() ]]]);

        } catch (\Exception $e) {
            
            dd($e->getMessage());

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }


    /**
     * @version  [<商户推荐 朋友圈 聊天 海报 申请pos机器>]
     * @author   Pudding   
     * @DateTime 2020-04-08T17:17:40+0800
     * @param    Request
     * @return   [type]
     */
    public function extendTemail(Request $request)
    {
        try{
            /** 获取分享类型的素材  */
            $list = \App\Share::where('active', '1')->where('type', '4')->first();

            if(!$list or empty($list))
                return response()->json(['success'=>['message' => '获取成功!', 'data' => array()]]);

            // 生成分享地址
            $Url = env('APP_URL')."/extendTemail/".Hashids::encode($request->user->id);
            
            // 二维码地址
            $CodePath = public_path('/share/'.$request->user->id.'/qrcodes/');
            // 目录不存在则重建
            File::isDirectory($CodePath) or File::makeDirectory($CodePath, 0777, true, true);
            //二维码文件
            $CodeFile = $CodePath."qrcode.png";
            // 生成二维码
            QrCode::format('png')->size($list->code_width)->margin($list->code_margin)->generate($Url, $CodeFile);


            $typeArr=getimagesize(storage_path('app/public/'.$list->image));

            switch($typeArr['mime'])
            {
                case "image/png":
                    $BackGroud=imagecreatefrompng(storage_path('app/public/'.$list->image));
                    break;

                case "image/jpg":
                    $BackGroud=imagecreatefromjpeg(storage_path('app/public/'.$list->image));
                    break;
                case "image/jpeg":
                    $BackGroud=imagecreatefromjpeg(storage_path('app/public/'.$list->image));
                    break;

                case "image/gif":
                    $BackGroud=imagecreatefromgif(storage_path('app/public/'.$list->image));
                    break;
            }

            $qrcode    =  imagecreatefrompng($CodeFile);

            imagecopyresampled($BackGroud, $qrcode, $list->pos_x, $list->pos_y, 0, 0, imagesx($qrcode), imagesy($qrcode), imagesx($qrcode), imagesy($qrcode));

            // 海报生成位置
            $PicPath   = public_path('/share/'.$request->user->id.'/temail_share/');
            File::isDirectory($PicPath) or File::makeDirectory($PicPath, 0777, true, true);

            $PicFile   = $PicPath.$list->id.".png";

            imagepng($BackGroud, $PicFile);

            $link = env('APP_URL')."/share/".$request->user->id."/temail_share/".$list->id.".png";

            return response()->json(['success'=>['message' => '获取成功!', 'data' => ['link' => $link."?time=".time() ]]]);

        } catch (\Exception $e) {

            return response()->json(['error'=>['message' => '系统错误,联系客服!']]);

        }
    }




}
