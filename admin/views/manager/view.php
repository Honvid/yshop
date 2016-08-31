<?php
/**
 * Created by PhpStorm.
 * User: Honvid
 * Date: 16/4/26
 * Time: 下午3:01
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

$this->title = $title;
?>

    <section class="content-header">
        <h1>
            管理员管理
            <small><?= Html::encode($this->title) ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/manager/index"> 管理员管理</a></li>
            <li class="active"><?= Html::encode($this->title) ?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="pull-right">
                    <a href="/manager/create" class="btn btn-default" title="添加管理员"><span class="icon glyphicon glyphicon-plus"></span> 添加管理员</a>
                    <a href="/manager/index" class="btn btn-default" title="管理员列表"><span class="fa fa-reply"></span> 管理员列表</a>
                </div>
            </div>
            <div class="box-body">
                <form class="form" id="edit-manager">
                    <div class="col-sm-8 form-group">
                        <label for="user_name" class="col-sm-2 control-label">用户名</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="user_name" id="user_name" placeholder="用户名" value="<?php echo !empty($user) ? $user['user_name'] : '';?>">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo !empty($user) ? $user['user_id'] : 0;?>">
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="role" class="col-sm-2 control-label">选择角色</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" id="role" name="role">
                                <option value="0">请选择用户所属角色</option>
                                <?php foreach ($list as $key => $value) { ?>
                                    <option value="<?php echo $value['role_id']; ?>"<?php echo !empty($user) && $user['role'] == $value['role_id'] ? ' selected' : '';?>><?php echo $value['role_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="email" class="col-sm-2 control-label">邮箱</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="email" id="email" placeholder="邮箱" value="<?php echo !empty($user) ? $user['email'] : '';?>">
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="password" class="col-sm-2 control-label">登陆密码</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="password" id="password" placeholder="请输入登陆密码">
                            <?php echo !empty($user) ? '<label class="text-red">如果不需要更新密码,则留空.</label>' : ''; ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <div class="col-sm-8">
                    <div class="pull-left">
                        <a href="/manager/index" class="btn btn-default">取消</a>
                        <a href="javascript:;" class="btn btn-info manager-edit">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php AppAsset::addJs($this,'@web/js/jquery.form.js'); ?>
<?php $this->beginBlock('script'); ?>
    <script>
        $('.select2').select2();
        $(".manager-edit").click(function(){
            if($("#user_name").val() == ""){
                swal({"title":"用户名必须填写","confirmButtonText":"确定", "type":"warning"});
                return false;
            }
            $("#edit-manager").ajaxSubmit({
                type: "post",
                url:"/manager/update",
                success: function (data) {
                    if(data.code == 1){
                        swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                            window.location.href = "/manager/index";
                        });
                    }else{
                        swal({"title":data.msg,"confirmButtonText":"确定", "type":"warning"});
                    }
                },
                error: function () {
                    swal({"title":"请求失败，请刷新后重试！","confirmButtonText":"确定", "type":"error"});
                }

            });
        });
    </script>
<?php $this->endBlock(); ?>