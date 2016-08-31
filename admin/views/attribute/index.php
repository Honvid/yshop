<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$this->title = "属性列表";

?>
<section class="content-header">
                    <h1>
                        属性管理
                        <small><?= Html::encode($this->title) ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                        <li><a href="/attribute/index"> 属性管理</a></li>
                        <li class="active"><?= Html::encode($this->title) ?></li>
                    </ol>
                </section>
                <section class="content">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                            <div class="pull-right">
                                <a href="/attribute/create" class="btn btn-default" title="添加属性"><span class="icon glyphicon glyphicon-plus"></span> 添加属性</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="grid" class="table table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th data-column-id="attr_id" data-identifier="true" data-type="numeric" data-order="asc">编号</th>
                                        <th data-column-id="attr_name" data-formatter="link">名称</th>
                                        <th data-column-id="cat_name" data-sortable="false">商品类型</th>
                                        <th data-column-id="attr_input_type" data-formatter="type">属性值的录入方式</th>
                                        <th data-column-id="attr_values" data-sortable="false">可选值列表</th>
                                        <th data-column-id="sort_order">排序</th>
                                        <th data-column-id="commands" data-formatter="commands" data-sortable="false">操作</th>
                                    </tr>
                                </thead>
                            </table>
                            <input type="hidden" value="<?php echo !empty($id) ? $id : 0; ?>" id="cat_id">
                        </div>
                </section>
                <div class="template" style="display: none;">
                    <div class="form-group" style="margin: 0; width: 250px;">
                        <div class="col-sm-12">
                            <select class="form-control select2" style="position: absolute;">
                                <option value="0">所有商品类型</option>
                                <?php foreach ($type_list as $key => $value) { ?>
                                    <option value="<?php echo $value['cat_id']; ?>"
                                        <?php
                                        if(!empty($id) && $id == $value['cat_id']) {
                                            echo "selected";
                                        }
                                        ?>>
                                        <?php
                                        echo $value['cat_name']
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
<?php $this->beginBlock('script'); ?>
    <script>
        var data = [];
        var grid = $("#grid").bootgrid({
            ajax: true,
            url: "/attribute/list",
            post: function (){
                return {
                    id: $("#cat_id").val()
                };
            },
            templates: {
                header: "<div id=\"{{ctx.id}}\" class=\"{{css.header}}\"><div class=\"row\"><div class=\"col-sm-12 actionBar\"><div id=\"template\">"+$('.template').html()+"</div><p class=\"{{css.search}}\"></p><p class=\"{{css.actions}}\"></p></div></div></div>"
            },
            formatters: {
                "commands": function(column, row){
                    return "<a href=\"/attribute/view?id=" + row.attr_id + "\" class=\"btn btn-xs btn-info command-edit\" data-row-id=\"" + row.attr_id + "\"><span class=\"fa fa-pencil\"></span></a> " +
                        "<a href=\"javascript:;\" class=\"btn btn-xs btn-warning command-delete\" data-row-id=\"" + row.attr_id + "\"><span class=\"fa fa-trash-o\"></span></a>";
                },
                "link": function(column, row){
                    return "<a href=\"/attribute/view?id="+row.attr_id+"\">" + row.attr_name + "</a>";
                },
                "type": function (column, row) {
                   if(row.attr_input_type == 1){
                       return "从列表中选择";
                   } else {
                       return "手工录入";
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
                            url:"/attribute/delete",
                            success: function (data) {
                                if(data.code == 1){
                                    swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                                        window.location.href = "/attribute/index";
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
        $(".select2").select2();
        $(".select2-container").css('width', '180px');
        $("#template").find('select').change(function(){
            window.location.href = "/attribute/index?id="+ $(this).val();
        });
    </script>
<?php $this->endBlock(); ?>