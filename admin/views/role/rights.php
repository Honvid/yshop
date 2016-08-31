<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use common\vendor\icheck\ICheckAsset;
use yii\helpers\Html;

AppAsset::register($this);
ICheckAsset::register($this);

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
            <li class="active"><?= Html::encode($this->title) ?> - <?= Html::encode($role['role_name']) ?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($role['role_name']) ?> - <small><?= Html::encode($this->title) ?></small></h3>
                <div class="pull-right">
                    <a href="/role/create" class="btn btn-default" title="添加角色"><span class="icon glyphicon glyphicon-plus"></span> 添加角色</a>
                    <a href="/role/index" class="btn btn-default" title="角色列表"><span class="fa fa-reply"></span> 角色列表</a>
                </div>
            </div>
            <div class="box-body">
                <form class="form" id="edit-role">
                <?php foreach ($route as $key => $item) {?>
                    <section>
                        <label class="col-xs-12 page-header"><input type="checkbox"<?php echo is_array($rights) && in_array($item['route'], $rights) ? ' checked':''; ?> name="route_list[]" level="<?php echo $item['level']; ?>" class="all_<?php echo $key; ?>" value="<?php echo $item['route']; ?>"><?php echo $item['title']; ?></label>
                        <div class="col-xs-12">
                    <?php if(!empty($item['controllers'])) foreach($item['controllers'] as $k => $c){ ?>
                        <section>
                            <label class="col-xs-12 page-header"><input type="checkbox"<?php echo is_array($rights) && in_array($c['route'], $rights) ? ' checked':''; ?> name="route_list[]" level="<?php echo $c['level']; ?>" class="all_<?php echo $key.'_'.$k; ?>" value="<?php echo $c['route']; ?>"><?php echo $c['title']; ?></label>
                            <div class="col-xs-12">
                                <?php if(!empty($c['actions'])) foreach($c['actions'] as $ke => $a){ ?>
                                    <div class="col-md-4 col-sm-6">
                                        <label class="checkbox"><input type="checkbox"<?php echo is_array($rights) && in_array($a['route'], $rights) ? ' checked':''; ?> name="route_list[]" level="<?php echo $a['level']; ?>" class="all_<?php echo $key.'_'.$k.'_'.$ke; ?>" value="<?php echo $a['route']; ?>"><?php echo $c['title'] . '-' . $a['desc']; ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </section>
                    <?php } ?>
                        </div>
                    </section>
                <?php } ?>
                    <input type="hidden" name="role_id" value="<?php echo !empty($role) ? $role['role_id'] : 0;?>">
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
            $("#edit-role").ajaxSubmit({
                type: "post",
                url:"/role/save",
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
        $("input").iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        }).on('ifChecked ifUnchecked', function(event){
            var class_name = event.target.className.split("_");
            var parent = null;
            var parent_node = null;
            var child = null;
            switch(class_name.length){
                case 2:
                    child = $('input[class^='+event.target.className+'_]');
                    if(event.type == 'ifChecked'){
                        child.iCheck('check');
                    }else{
                        child.iCheck('uncheck');
                    }
                    break;
                case 3:
                    parent = $('input[class^='+class_name[0]+'_'+class_name[1]+'][level=2]');
                    parent_node = $('input.'+class_name[0]+'_'+class_name[1]);
                    child = $('input[class^='+event.target.className+'_]');
                    if(event.type == 'ifChecked'){
                        child.iCheck('check');
                        if(parent.length == parent.filter(':checked').length){
                            parent_node.prop('checked', 'checked');
                        }
                    }else{
                        child.iCheck('uncheck');
                        parent_node.prop('checked',false);
                    }
                    parent_node.iCheck('update');
                    break;
                case 4:
                    parent_node = $('input.'+class_name[0]+'_'+class_name[1]+'_'+class_name[2]);
                    child = $('input[class^='+class_name[0]+'_'+class_name[1]+'_'+class_name[2]+'_]');
                    var parent_parent = $('input[class^='+class_name[0]+'_'+class_name[1]+'][level=2]');
                    var parent__parent_node = $('input.'+class_name[0]+'_'+class_name[1]);
                    if(event.type == 'ifChecked'){
                        if(child.length == child.filter(':checked').length){
                            parent_node.prop('checked', 'checked');
                        }
                        if(parent_parent.length == parent_parent.filter(':checked').length){
                            parent__parent_node.prop('checked', 'checked');
                        }
                    }else{
                        parent_node.prop('checked',false);
                        parent__parent_node.prop('checked',false);
                    }
                    parent_node.iCheck('update');
                    parent__parent_node.iCheck('update');
                    break;
            }
        });
    </script>
<?php $this->endBlock(); ?>