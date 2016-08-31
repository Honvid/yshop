<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$this->title = "商品列表";
?>
<section class="content-header">
                    <h1>
                        商品管理
                        <small><?= Html::encode($this->title) ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                        <li><a href="/goods/index"> 商品管理</a></li>
                        <li class="active"><?= Html::encode($this->title) ?></li>
                    </ol>
                </section>
                <section class="content">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                            <div class="pull-right">
                                <a href="/goods/create" class="btn btn-default" title="添加商品"><span class="icon glyphicon glyphicon-plus"></span> 添加商品</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="grid" class="table table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th data-column-id="goods_id" data-identifier="true" data-type="numeric" data-order="desc">编号</th>
                                        <th data-column-id="goods_name" data-formatter="link">商品名称</th>
                                        <th data-column-id="goods_sn">货号</th>
                                        <th data-column-id="shop_price">价格</th>
                                        <th data-column-id="is_on_sale" data-formatter="status">上架</th>
                                        <th data-column-id="is_best" data-formatter="status">精品</th>
                                        <th data-column-id="is_new" data-formatter="status">新品</th>
                                        <th data-column-id="is_hot" data-formatter="status">热销</th>
                                        <th data-column-id="sort_order">推荐排序</th>
                                        <th data-column-id="goods_number">库存</th>
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
            url: "/goods/list",
            selection: true,
            multiSelect: true,
            formatters: {
                "commands": function(column, row){
                    return "<a href=\"/goods/view?id=" + row.goods_id + "\" class=\"btn btn-xs btn-info command-edit\" data-row-id=\"" + row.goods_id + "\"><span class=\"fa fa-pencil\"></span></a> " +
                        "<a href=\"javascript:;\" class=\"btn btn-xs btn-warning command-delete\" data-row-id=\"" + row.goods_id + "\"><span class=\"fa fa-trash-o\"></span></a>";
                },
                "link": function(column, row){
                    return "<a href=\"/goods/view?id="+row.goods_id+"\">" + row.goods_name + "</a>";
                },
                "status": function(column, row){
                    var status = 1;
                    switch(column.id){
                        case "is_on_sale":
                            status = row.is_on_sale;
                            break;
                        case "is_best":
                            status = row.is_best;
                            break;
                        case "is_new":
                            status = row.is_new;
                            break;
                        case "is_hot":
                            status = row.is_hot;
                            break;
                    }
                    if(status == 1){
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
                            url:"/category/delete",
                            success: function (data) {
                                if(data.code == 1){
                                    swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                                        window.location.href = "/category/index";
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