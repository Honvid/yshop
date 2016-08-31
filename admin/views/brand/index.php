<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$this->title = "品牌列表";
?>
<section class="content-header">
                    <h1>
                        品牌管理
                        <small><?= Html::encode($this->title) ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                        <li><a href="/brand/index"> 品牌管理</a></li>
                        <li class="active"><?= Html::encode($this->title) ?></li>
                    </ol>
                </section>
                <section class="content">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                            <div class="pull-right">
                                <a href="/brand/create" class="btn btn-default" title="添加品牌"><span class="icon glyphicon glyphicon-plus"></span> 添加品牌</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="grid" class="table table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th data-column-id="brand_id" data-identifier="true" data-type="numeric" data-order="desc">编号</th>
                                        <th data-column-id="brand_name" data-formatter="link">名称</th>
                                        <th data-column-id="brand_logo">LOGO</th>
                                        <th data-column-id="brand_desc">描述</th>
                                        <th data-column-id="site_url">官网</th>
                                        <th data-column-id="sort_order">排序</th>
                                        <th data-column-id="is_show" data-formatter="status">是否显示</th>
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
            url: "/brand/list",
            selection: true,
            multiSelect: true,
            formatters: {
                "commands": function(column, row){
                    return "<a href=\"/brand/view?id=" + row.brand_id + "\" class=\"btn btn-xs btn-info command-edit\" data-row-id=\"" + row.brand_id + "\"><span class=\"fa fa-pencil\"></span></a> " +
                        "<a href=\"javascript:;\" class=\"btn btn-xs btn-warning command-delete\" data-row-id=\"" + row.brand_id + "\"><span class=\"fa fa-trash-o\"></span></a>";
                },
                "link": function(column, row){
                    return "<a href=\"/brand/view?id="+row.brand_id+"\">" + row.brand_name + "</a>";
                },
                "status": function(column, row){
                    if(row.is_show == 1){
                        return "<span class=\"fa fa-check text-green\"></span>";
                    }else{
                        return "<span class=\"fa fa-times text-yellow\"></span>";
                    }
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
                            url:"/brand/delete",
                            success: function (data) {
                                if(data.code == 1){
                                    swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                                        window.location.href = "/brand/index";
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
        }).on("selected.rs.jquery.bootgrid", function (e, selectedRows)
        {
            var row;
            for (var i = 0; i < selectedRows.length; i++)
            {
                row = selectedRows[i];

                // Array.contains is an extension of bootgrid
                if (!data.contains(function (item) { return item.id === row.id; }))
                {
                    // quantity = grid.find("#" + row.id + "-quantity").val();
                    data.push({ id: row.id});
                    console.log(data);
                }
            }
        }).on("deselected.rs.jquery.bootgrid", function (e, deselectedRows)
        {
            var row;
            for (var i = 0; i < deselectedRows.length; i++)
            {
                row = deselectedRows[i];
                for (var j = 0; j < data.length; j++)
                {
                    if (data[j].id === row.id)
                    {
                        data.splice(j, 1);
                        return;
                    }
                }
            }
        });
    </script>
<?php $this->endBlock(); ?>