<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

$this->title = $title;
?>

<section class="content-header">
                    <h1>
                        类型管理
                        <small><?= Html::encode($this->title) ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="/site/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                        <li><a href="/goodstype/index"> 类型管理</a></li>
                        <li class="active"><?= Html::encode($this->title) ?></li>
                    </ol>
                </section>
                <section class="content">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                            <div class="pull-right">
                                <a href="/goodstype/create" class="btn btn-default" title="添加类型"><span class="icon glyphicon glyphicon-plus"></span> 添加类型</a>
                                <a href="/goodstype/index" class="btn btn-default" title="商品类型"><span class="fa fa-reply"></span> 商品类型</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <form class="form" id="edit-type" enctype="multipart/form-data">
                                <div class="col-sm-8 form-group">
                                    <label for="cat_name" class="col-sm-2 control-label">类型名称</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="类型名称" value="<?php echo !empty($type) ? $type['cat_name'] : '';?>">
                                    </div>
                                    <input type="hidden" name="cat_id" value="<?php echo !empty($type) ? $type['cat_id'] : 0;?>">
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="attr_group" class="col-sm-2 control-label">属性分组</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" rows="5" name="attr_group" id="attr_group"><?php echo !empty($type) ? str_replace(',', "\r\n", $type['attr_group']) : '';?></textarea>
                                        <label>每行一个商品属性组。排序也将按照自然顺序排序。</label>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="enabled" class="col-sm-2 control-label">是否可用</label>
                                    <div class="col-sm-10">
                                        <div>
                                            <label><input style="margin: 0 5px;" type="radio" name="enabled" id="enabled" value="1" <?php echo !empty($type) && ($type['enabled'] == 1) ? 'checked' : '';?>>是</label>
                                            <label style="padding-left: 10px;"><input style="margin: 0 5px;" type="radio" name="enabled" value="0" <?php echo !empty($type) && ($type['enabled'] == 0) ? 'checked' : '';?>>否</label>
                                            <label style="padding: 5px 0;">(不可用的类型，在添加商品的时候选择商品属性将不可选)</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-8">
                                <div class="pull-left">
                                    <a href="/goodstype/index" class="btn btn-default">取消</a>
                                    <a href="javascript:;" class="btn btn-info type-edit">保存</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
<?php AppAsset::addJs($this,'@web/js/jquery.form.js'); ?>
<?php $this->beginBlock('script'); ?>
    <script>
        $(".type-edit").click(function(){
            if($("#cat_name").val() == ""){
                swal({"title":"类型名称必须填写","confirmButtonText":"确定", "type":"warning"});
                return false;
            };
            $("#edit-type").ajaxSubmit({
                type: "post",
                url:"/goodstype/update",
                success: function (data) {
                    if(data.code == 1){
                        swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                            window.location.href = "/goodstype/index";
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