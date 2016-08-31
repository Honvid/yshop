<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
AppAsset::addCss($this,'@web/js/treegrid/jquery.treegrid.css');

$this->title = "分类列表";
?>
<section class="content-header">
                    <h1>
                        分类管理
                        <small><?= Html::encode($this->title) ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                        <li><a href="/category/index"> 分类管理</a></li>
                        <li class="active"><?= Html::encode($this->title) ?></li>
                    </ol>
                </section>
                <section class="content">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                            <div class="pull-right">
                                <a href="/category/create" class="btn btn-default" title="添加分类"><span class="icon glyphicon glyphicon-plus"></span> 添加分类</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="grid" class="table table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>分类名称</th>
                                        <th>商品数量</th>
                                        <th>数量单位</th>
                                        <th>导航栏</th>
                                        <th>是否显示</th>
                                        <th>价格分级</th>
                                        <th>排序</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list as $key => $value) { ?>
                                        <tr class="treegrid-<?php echo $value['cat_id'];?> <?php echo !empty($value['parent_id']) ? 'treegrid-parent-'.$value['parent_id'] : '';?>">
                                            <td><?php echo $value['cat_name']; ?></td>
                                            <td><?php echo $value['goods_num']; ?></td>
                                            <td><?php echo $value['measure_unit']; ?></td>
                                            <td><?php echo $value['show_in_nav']; ?></td>
                                            <td><span class="<?php echo $show[$value['is_show']]; ?>"></span></td>
                                            <td><?php echo $value['grade']; ?></td>
                                            <td><?php echo $value['sort_order']; ?></td>
                                            <td>
                                                <a href="/category/view?id=<?php echo $value['cat_id']; ?>" class="btn btn-xs btn-info" title="编辑"><span class="fa fa-pencil"></span></a>
                                                <a href="/category/move?id=<?php echo $value['cat_id']; ?>" cid="<?php echo $value['cat_id']; ?>" class="btn btn-xs btn-danger" title="转移商品"><span class="fa fa-exchange"></span></a>
                                                <a href="javascript:;" cid="<?php echo $value['cat_id']; ?>" class="btn btn-xs btn-warning command-delete" title="删除"><span class="fa fa-trash-o"></span></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                </section>
<?php AppAsset::addJs($this,'@web/js/treegrid/jquery.treegrid.js'); ?>
<?php $this->beginBlock('script'); ?>
    <script>
        $("#grid").treegrid();
        $(".command-delete").on("click", function(e){
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
                        data: {"id":$this.attr("cid")},
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
    </script>
<?php $this->endBlock(); ?>