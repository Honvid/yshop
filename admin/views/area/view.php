<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use common\vendor\ztree\ZTreeAsset;
use yii\helpers\Html;

ZTreeAsset::register($this);
AppAsset::register($this);

$this->title = $title;
?>

    <section class="content-header">
        <h1>
            区域管理
            <small><?= Html::encode($this->title) ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/area/index"> 区域管理</a></li>
            <li class="active"><?= Html::encode($this->title) ?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="pull-right">
                    <a href="/area/create" class="btn btn-default" title="添加区域"><span class="icon glyphicon glyphicon-plus"></span> 添加区域</a>
                    <a href="/area/index" class="btn btn-default" title="区域列表"><span class="fa fa-reply"></span> 区域列表</a>
                </div>
            </div>
            <div class="box-body">
                <form class="form" id="edit-area">
                    <div class="col-sm-8 form-group">
                        <label for="name" class="col-sm-2 control-label">名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" placeholder="名称" value="<?php echo !empty($area) ? $area['name'] : '';?>">
                        </div>
                        <input type="hidden" name="id" value="<?php echo !empty($area) ? $area['id'] : 0;?>">
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="short_name" class="col-sm-2 control-label">简称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="short_name" id="short_name" placeholder="简称" value="<?php echo !empty($area) ? $area['short_name'] : '';?>">
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="parent" class="col-sm-2 control-label">父级区域</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" name="parent" readonly id="parent" placeholder="父级区域" value="<?php echo !empty($parent) ? $parent['name'] : '';?>">
                                <input type="hidden" class="form-control" name="parent_id" readonly id="parent_id" value="<?php echo !empty($area) ? $area['parent_id'] : 0;?>">
                                <input type="hidden" class="form-control" name="level" readonly id="level" value="<?php echo !empty($area) ? $area['level'] : 1;?>">
                                <span class="input-group-btn">
                                    <a href="javascript:;" onclick="showMenu(); return false;" class="btn btn-info btn-flat">选择</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="longitude" class="col-sm-2 control-label">经度</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="longitude" id="longitude" placeholder="经度" value="<?php echo !empty($area) ? $area['longitude'] : '';?>">
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="latitude" class="col-sm-2 control-label">纬度</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="latitude" id="latitude" placeholder="纬度" value="<?php echo !empty($area) ? $area['latitude'] : '';?>">
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="sort" class="col-sm-2 control-label">排序</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="sort" id="sort" placeholder="排序" value="<?php echo !empty($area) ? $area['sort'] : '';?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <div class="col-sm-8">
                    <div class="pull-left">
                        <a href="/area/index" class="btn btn-default">取消</a>
                        <a href="javascript:;" class="btn btn-info area-edit">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="menuContent" class="menuContent" style="display:none; position: absolute;">
        <ul id="treeDemo" class="ztree"></ul>
    </div>
<?php
AppAsset::addJs($this,'@web/js/jquery.form.js');
?>
<?php $this->beginBlock('script'); ?>
    <script>
        var setting = {
            async: {
                enable: true,
                url: '/area/children',
                autoParam: ["id"]
            },
            view: {
                showLine:true,
                dblClickExpand: false
            },
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id", // id编号命名 默认
                    pIdKey: "parent_id", // 父id编号命名 默认
                    rootPId: 0 // 用于修正根节点父节点数据，即 pIdKey 指定的属性值
                }
            },
            callback: {
                onAsyncSuccess: onAsyncSuccess,
                onAsyncError: onAsyncError,
                onClick: onClick
            }
        };

        function onAsyncSuccess(event, treeId, treeNode, msg) {
            if (!msg || msg.length == 0) {
                return;
            }
            var zTree = $.fn.zTree.getZTreeObj(treeId);
            zTree.updateNode(treeNode);
            zTree.selectNode(treeNode.children[0]);
        }
        function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown) {
            var zTree = $.fn.zTree.getZTreeObj(treeId);
            swal({"title":"异步获取数据出现异常","confirmButtonText":"确定", "type":"warning"});
            zTree.updateNode(treeNode);
        }

        function onClick(e, treeId, treeNode) {
            $("#parent").val(treeNode.name);
            $("#parent_id").val(treeNode.id);
            $("#level").val(treeNode.level+2);
        }

        function showMenu() {
            var cityObj = $("#parent");
            $("#treeDemo").css("width", cityObj.outerWidth());
            var cityOffset = cityObj.offset();
            $("#menuContent").css({left:cityOffset.left + "px", top:cityOffset.top + cityObj.outerHeight() + "px", "z-index":"999"}).slideDown("fast");
            $("body").bind("mousedown", onBodyDown);
        }
        function hideMenu() {
            $("#menuContent").fadeOut("fast");
            $("body").unbind("mousedown", onBodyDown);
        }
        function onBodyDown(event) {
            if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
                hideMenu();
            }
        }

        $(document).ready(function(){
            $.ajax({
                type: "post",
                url:"/area/list",
                success: function (data) {
                    if(data.code == 1){
                        var list = data.data;
                        $.fn.zTree.init($("#treeDemo"), setting, list);
                    }
                },
                error: function () {
                    swal({"title":"请求失败，请刷新后重试！","confirmButtonText":"确定", "type":"error"});
                }
            });
        });
        $(".area-edit").click(function(){
            if($("#name").val() == ""){
                swal({"title":"名称必须填写","confirmButtonText":"确定", "type":"warning"});
                return false;
            };
            $("#edit-area").ajaxSubmit({
                type: "post",
                url:"/area/update",
                success: function (data) {
                    if(data.code == 1){
                        swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                            window.location.href = "/area/index";
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