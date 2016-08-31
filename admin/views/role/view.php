<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

$this->title = $title;
?>

    <section class="content-header">
        <h1>
            角色管理
            <small><?= Html::encode($this->title) ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/role/index"> 角色管理</a></li>
            <li class="active"><?= Html::encode($this->title) ?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="pull-right">
                    <a href="/role/create" class="btn btn-default" title="添加角色"><span class="icon glyphicon glyphicon-plus"></span> 添加角色</a>
                    <a href="/role/index" class="btn btn-default" title="角色列表"><span class="fa fa-reply"></span> 角色列表</a>
                </div>
            </div>
            <div class="box-body">
                <form class="form" id="edit-role">
                    <div class="col-sm-8 form-group">
                        <label for="role_name" class="col-sm-2 control-label">名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="role_name" id="role_name" placeholder="名称" value="<?php echo !empty($role) ? $role['role_name'] : '';?>">
                        </div>
                        <input type="hidden" name="role_id" value="<?php echo !empty($role) ? $role['role_id'] : 0;?>">
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="desc" class="col-sm-2 control-label">描述</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="5" name="desc" id="desc"><?php echo !empty($role) ? $role['desc'] : '';?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="sort" class="col-sm-2 control-label">排序</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="sort" id="sort" placeholder="排序" value="<?php echo !empty($role) ? $role['sort'] : '';?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <div class="col-sm-8">
                    <div class="pull-left">
                        <a href="/role/index" class="btn btn-default">取消</a>
                        <a href="javascript:;" class="btn btn-info role-edit">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
AppAsset::addJs($this,'@web/js/jquery.form.js');
?>
<?php $this->beginBlock('script'); ?>
    <script>
        $(".role-edit").click(function(){
            if($("#role_name").val() == ""){
                swal({"title":"名称必须填写","confirmButtonText":"确定", "type":"warning"});
                return false;
            };
            $("#edit-role").ajaxSubmit({
                type: "post",
                url:"/role/update",
                success: function (data) {
                    if(data.code == 1){
                        swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                            window.location.href = "/role/index";
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