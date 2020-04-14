@extends('layouts.apps')



@section('content')
<div class="weui-msg">
    <div class="weui-msg__icon-area">
        <i class="weui-icon-success weui-icon_msg"></i>
    </div>

    <div class="weui-msg__text-area">
        <h2 class="weui-msg__title">注册成功</h2>
        <p class="weui-msg__desc">
            请关注公众号并下载APP
        </p>
    </div>

    <div class="weui-msg__opr-area">
        <p class="weui-btn-area">
            <a href="javascript:;" class="weui-btn weui-btn_primary">关注公众号</a>
            <a href="{{ config('base.app_down_url') }}" class="weui-btn weui-btn_default">下载APP</a>
        </p>
    </div>

    <div class="weui-msg__extra-area">
        <div class="weui-footer">
            <p class="weui-footer__links">
                <a href="javascript:void(0);" class="weui-footer__link">{{ env('APP_NAME')}}版权所有</a>
            </p> 
            <p class="weui-footer__text">Copyright © 2020-2025 {{ env('APP_NAME')}}</p>
        </div>
    </div>
</div>
@endsection



