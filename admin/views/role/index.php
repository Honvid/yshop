<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$this->title = "角色列表";
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
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="grid" class="table table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th data-column-id="role_id" data-identifier="true" data-type="numeric" data-order="asc">编号</th>
                                        <th data-column-id="role_name" data-formatter="link">名称</th>
                                        <th data-column-id="desc">描述</th>
                                        <th data-column-id="sort">排序</th>
                                        <th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                </section>
<?php $this->beginBlock('script'); ?>
    <script>
        var data = [];
        var grid = $("#grid").bootgrid({
            ajax: true,
            url: "/role/list",
            selection: true,
            multiSelect: true,
            formatters: {
                "commands": function(column, row){
                    return "<a href=\"/role/view?id=" + row.role_id + "\" class=\"btn btn-xs btn-info command-edit\" data-row-id=\"" + row.role_id + "\" title=\"编辑角色\"><span class=\"fa fa-pencil\"></span></a> " +
                        "<a href=\"/role/rights?id=" + row.role_id + "\" class=\"btn btn-xs btn-danger command-set\" data-row-id=\"" + row.role_id + "\" title=\"配置权限\"><span class=\"fa fa-lock\"></span></a> " +
                        "<a href=\"javascript:;\" class=\"btn btn-xs btn-warning command-delete\" data-row-id=\"" + row.role_id + "\" title=\"删除角色\"><span class=\"fa fa-trash-o\"></span></a>";
                },
                "link": function(column, row){
                    return "<a href=\"/role/view?id="+row.role_id+"\">" + row.role_name + "</a>";
                }
            }
        }).on("loaded.rs.jquery.bootgrid", function (e){
            data = [];
            grid.find(".command-delete").on("click", function(e){
                var $this = $(this);
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
                            data: {"id":$this.data("row-id")},
                            url:"/role/delete",
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
                    }
                });
            });
        });
    </script>
<?php $this->endBlock(); ?>