<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use common\vendor\fancytree\FancyTreeAsset;
use yii\helpers\Html;

FancyTreeAsset::register($this);
AppAsset::register($this);
AppAsset::addCss($this,'@web/js/treegrid/jquery.treegrid.css');

$this->title = "区域列表";
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
                </div>
            </div>
            <div class="box-body">
                <table id="treetable" class="table table-condensed table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>名称</th>
                        <th>简称</th>
                        <th>等级</th>
                        <th>经度</th>
                        <th>纬度</th>
                        <th>是否启用</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
    </section>
<?php AppAsset::addJs($this,'@web/js/treegrid/jquery.treegrid.js'); ?>
<?php $this->beginBlock('script'); ?>
    <script>
        glyph_opts = {
            map: {
                doc: "fa fa-circle-o",
                docOpen: "fa fa-circle-o",
                checkbox: "fa fa-square-o",
                checkboxSelected: "fa fa-check-square-o",
                checkboxUnknown: "fa fa-square",
                dragHelper: "fa arrow-right",
                dropMarker: "fa long-arrow-right",
                error: "fa fa-warning",
                expanderClosed: "fa fa-plus-square",
                expanderLazy: "fa fa-plus-square",
                expanderOpen: "fa fa-minus-square",
                folder: "fa fa-folder-o",
                folderOpen: "fa fa-folder-open-o",
                loading: "fa fa-spinner fa-pulse"
            }
        };
        $("#treetable").fancytree({
            extensions: ["glyph", "table"],
            checkbox: false,
            selectMode: 3,
            glyph: glyph_opts,
            source: {
                url: "/area/list"
//                debugDelay: 1000
            },
            postProcess: function(event, data) {
                data.result = data.response.data;
            },
            table: {
                nodeColumnIdx: 1     // render the node title into the 2nd column
            },
            activate: false,
            lazyLoad: function(event, data) {
                data.result = {
                    url: "/area/children",
//                    debugDelay: 1000,
                    data: {
                        id: data.node.data.id,
                        mode: "children"
                    }
                };
            },
            loadError:function(e,data){
                var error = data.error;
                if (error.status && error.statusText) {
                    data.message = "Ajax error: " + data.message;
                    data.details = "Ajax error: " + error.statusText + ", status code = " + error.status;
                } else {
                    data.message = "Custom error: " + data.message;
                    data.details = "An error occurred during loading: " + error;
                }
            },
            renderColumns: function(event, data) {
                var node = data.node,
                    $tdList = $(node.tr).find(">td");
                var status = "<span onclick=\"show("+node.data.id+","+node.data.status+")\" class=\"fa fa-times text-yellow\"></span>";
                if(node.data.status == 1){
                    status = "<span onclick=\"show("+node.data.id+","+node.data.status+")\" class=\"fa fa-check text-green\"></span>";
                }
                $tdList.eq(0).text(node.getIndexHier());
                $tdList.eq(2).text(node.data.short_name);
                $tdList.eq(3).text(node.data.level);
                $tdList.eq(4).text(node.data.longitude);
                $tdList.eq(5).text(node.data.latitude);
                $tdList.eq(6).html(status);
                $tdList.eq(7).text(node.data.sort);
//                $tdList.eq(7).html("<input type='input' value='" + node.data.sort + "'>");
                $tdList.eq(8).html("<a href=\"/area/view?id=" + node.data.id + "\" class=\"btn btn-xs btn-info command-edit\"><span class=\"fa fa-pencil\"></span></a> " +
                    "<a href=\"javascript:;\" onclick=\"del("+node.data.id+")\" class=\"btn btn-xs btn-warning command-delete\"><span class=\"fa fa-trash-o\"></span></a>");
            },
            select: function(event, data) {

            }
        });
        $(function(){
            $("#btnExpandAll").click(function(){
                $("#tree").fancytree("getTree").visit(function(node){
                    node.setExpanded(true);
                });
            });
            $("#btnCollapseAll").click(function(){
                $("#tree").fancytree("getTree").visit(function(node){
                    node.setExpanded(false);
                });
            });
        });

        function show(id, status){
            $.ajax({
                type: "post",
                data: {"id": id, "status":status},
                url:"/area/show",
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
        }

        function del(id){
            swal({
                "title":"您确定要删除吗？",
                "confirmButtonText":"确定",
                "cancelButtonText":"取消",
                showCancelButton: true,
                "closeOnConfirm":false
            },function(isConfirm){
                if(isConfirm){
                    $.ajax({
                        type: "post",
                        data: {"id": id},
                        url:"/area/delete",
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
                }
            });
        }
    </script>
<?php $this->endBlock(); ?>