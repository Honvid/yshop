<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
AppAsset::addCss($this,'@web/js/treegrid/jquery.treegrid.css');

$this->title = "菜单列表";
?>
    <section class="content-header">
        <h1>
            菜单管理
            <small><?= Html::encode($this->title) ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/menu/index"> 菜单管理</a></li>
            <li class="active"><?= Html::encode($this->title) ?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                <div class="pull-right">
                    <a href="/menu/create" class="btn btn-default" title="添加菜单"><span class="icon glyphicon glyphicon-plus"></span> 添加菜单</a>
                </div>
            </div>
            <div class="box-body">
                <table id="grid" class="table table-condensed table-hover table-striped">
                    <thead>
                    <tr>
                        <th>名称</th>
                        <th>规则</th>
                        <th>图标</th>
                        <th>描述</th>
                        <th>是否显示</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($list as $key => $value) { ?>
                        <tr class="treegrid-<?php echo $value['menu_id'];?> <?php echo !empty($value['parent_id']) ? 'treegrid-parent-'.$value['parent_id'] : '';?>">
                            <td> <?php echo !empty($value['icon']) ? "<span class=\"". $value['icon'] ."\"></span>" : ''; ?> <?php echo $value['menu_name']; ?></td>
                            <td><?php echo $value['menu_rule']; ?></td>
                            <td><?php echo $value['icon']; ?></td>
                            <td><?php echo $value['desc']; ?></td>
                            <td><span class="<?php echo $show[$value['is_show']]; ?>"></span></td>
                            <td><?php echo $value['sort']; ?></td>
                            <td>
                                <a href="/menu/view?id=<?php echo $value['menu_id']; ?>" class="btn btn-xs btn-info" title="编辑"><span class="fa fa-pencil"></span></a>
                                <a href="javascript:;" name="<?php echo $value['menu_id']; ?>" class="btn btn-xs btn-warning command-delete" title="删除"><span class="fa fa-trash-o"></span></a>
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
                        data: {"id":$this.attr("name")},
                        url:"/menu/delete",
                        success: function (data) {
                            if(data.code == 1){
                                swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                                    window.location.href = "/menu/index";
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