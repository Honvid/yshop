<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;

AppAsset::register($this);

$this->title = "转移商品";
?>
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Honvid</b> 商城管理系统</a>
    </div>
    <div class="login-box-body">
        <form action="/user/do" method="post" id="login-form">
            <p class="login-box-msg">请登陆</p>
            <div class="form-group has-feedback">
                <input type="text" name="name" class="form-control" placeholder="用户名">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="pwd" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input name="remember" type="checkbox" value="0"> 记住我的登陆状态
                            <input type="hidden" name="check" value="0">
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <a class="btn btn-primary btn-block btn-flat" id="login">登陆</a>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-sm-6"></div>
            <a href="#" class="col-sm-6 text-right">忘记密码?</a>
        </div>
    </div>
</div>
<?php AppAsset::addCss($this,'@web/js/iCheck/square/blue.css'); ?>
<?php AppAsset::addJs($this,'@web/js/iCheck/icheck.min.js'); ?>
<?php $this->beginBlock('script'); ?>
<script>
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
    $("#login").on("click", function(e){
        if($('input[name=name]').val() == '' ||  $('input[name=pwd]').val() == ''){
            swal({"title":"用户名或者密码不能为空","confirmButtonText":"确定", "type":"warning"});
            return false;
        }
        var name = $('input[name=name]').val();
        var pwd = $('input[name=pwd]').val();
        if($('input[name=remember]').prop('checked')){
            $('input[name=check]').val(1);
        }
        $('#login-form').submit();
    });
</script>
<?php $this->endBlock(); ?>