<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$this->title = "管理员列表";
?>
<section class="content-header">
                    <h1>
                        权限管理
                        <small><?= Html::encode($this->title) ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                        <li><a href="/manager/index"> 权限管理</a></li>
                        <li class="active"><?= Html::encode($this->title) ?></li>
                    </ol>
                </section>
                <section class="content">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                            <div class="pull-right">
                                <a href="/manager/create" class="btn btn-default" title="添加管理员"><span class="icon glyphicon glyphicon-plus"></span> 添加管理员</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="grid" class="table table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th data-column-id="user_id" data-identifier="true" data-type="numeric" data-order="asc">编号</th>
                                        <th data-column-id="user_name" data-formatter="link">用户名</th>
                                        <th data-column-id="role" data-formatter="role">角色</th>
                                        <th data-column-id="email">邮箱地址</th>
                                        <th data-column-id="create_at" data-formatter="addtime">创建时间</th>
                                        <th data-column-id="last_login" data-formatter="login">最后登陆</th>
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
            url: "/manager/list",
            selection: true,
            multiSelect: true,
            formatters: {
                "commands": function(column, row){
                    if(row.is_admin != 1) {
                        return "<a href=\"/manager/view?id=" + row.user_id + "\" class=\"btn btn-xs btn-info command-edit\" data-row-id=\"" + row.user_id + "\" title=\"编辑\"><span class=\"fa fa-pencil\"></span></a> " +
                            "<a href=\"javascript:;\" class=\"btn btn-xs btn-warning command-delete\" data-row-id=\"" + row.user_id + "\" title=\"删除\"><span class=\"fa fa-trash-o\"></span></a>";
                    }else{
                        return '';
                    }
                },
                "link": function(column, row){
                    return "<a href=\"/manager/view?id="+row.user_id+"\">" + row.user_name + "</a>";
                },
                "role": function(column, row){
                    return row.role_name;
                },
                "addtime":function(column, row){
                    var newdate = new Date();
                    return newdate.format(row.create_at, 'yyyy-MM-dd hh:mm:ss');
                },
                "login":function(column, row){
                    var newdate = new Date();
                    return newdate.format(row.last_login, 'yyyy-MM-dd hh:mm:ss');
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
                            url:"/manager/delete",
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
                    }
                });
            });
        });
        function unix_to_datetime(unix) {
            var now = new Date(parseInt(unix) * 1000);
            return now.toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
        }
    </script>
<?php $this->endBlock(); ?>