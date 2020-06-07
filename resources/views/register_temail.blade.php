@extends('layouts.apps')

@section('css')
<style type="text/css">
.weui-icon_toast.weui-loading{margin: .8rem 0 0;}

.weui-toast{width: 6rem; height: 5.5rem; min-height: 5.5rem; top: 33%; left: 45%}

.weui-icon_toast{font-size: 2rem; margin-bottom: .6rem}

.weui-toast_content{ font-size: .7rem; }
</style>
@endsection

@section('content')

<header class="demos-header">
    <h1 class="demos-title">Application machine</h1>
</header>

<form action="" method="post" name="register" id="register_form">
@csrf
<div class="weui-cells weui-cells_form">

    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">手机号</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="tel" placeholder="请输入您的手机号" name="register_phone" 
                value="{{old('register_phone')}}">
        </div>
    </div>


    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">姓名</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="text" placeholder="请输入您的姓名" name="register_name">
        </div>
    </div>


    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">地址</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="text" placeholder="请输入您的地址" name="register_address">
        </div>
    </div>

</div>


<div class="weui-btn-area">
    <button class="weui-btn weui-btn_primary" id="Register">立即申请</button>
</div>

</form>
@endsection

@section('javascript')
<script type="text/javascript">
@if(count($errors) > 0)
    $.toptip('{{ $errors->first() }}', 'error');
@endif
</script>
@endsection

