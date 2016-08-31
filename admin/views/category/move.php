<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

$this->title = "转移商品";
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
                    <a href="/category/view" class="btn btn-default" title="添加分类"><span class="icon glyphicon glyphicon-plus"></span> 添加分类</a>
                    <a href="/category/index" class="btn btn-default" title="分类列表"><span class="fa fa-reply"></span> 分类列表</a>
                </div>
            </div>
            <div class="box-body">
                <div class="alert alert-info alert-dismissable col-xs-8">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> 什么是转移商品分类?</h4>
                    在添加商品或者在商品管理中,如果需要对商品的分类进行变更,那么你可以通过此功能,正确管理你的商品分类。
                </div>
                <div class="col-sm-8 form-group">
                    <label class="col-sm-2 control-label text-right">从此分类</label>
                    <div class="col-sm-10">
                        <div class="col-sm-4" style="padding-left: 0;">
                            <select class="form-control select2" name="cat_id" style="position: absolute;">
                                <option value="0">请选择</option>
                                <?php foreach ($category_list as $key => $value) { ?>
                                    <option value="<?php echo $value['cat_id']; ?>" <?php echo $value['cat_id'] == $cat_id ? "selected": ''?>><?php echo $value['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="col-sm-2 text-center" style="line-height: 34px;">转移到</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="target_cat_id" style="position: absolute;">
                                <option value="0">请选择</option>
                                <?php foreach ($category_list as $key => $value) { ?>
                                    <option value="<?php echo $value['cat_id']; ?>"><?php echo $value['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <span class="input-group-btn">
                                <a href="javascript:;" class="btn btn-info btn-flat command-move">
                                    <span class="fa fa-exchange"></span>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
    </section>
<?php $this->beginBlock('script'); ?>
    <script>
        $(".select2").select2();
        $(".command-move").on("click", function(e){
            if($('select[name=cat_id]').val() == $('select[name=target_cat_id]').val()){
                swal({"title":"原分类与目标分类不能相同","confirmButtonText":"确定", "type":"warning"});
                return false;
            }
            swal({
                "title":"您确定要转移商品吗？",
                "confirmButtonText":"确定",
                "cancelButtonText":"取消",
                showCancelButton: true,
                "closeOnConfirm":false
            },function(isConfirm){
                if(isConfirm){
                    $.ajax({
                        type: "post",
                        data: {"cat_id":$('select[name=cat_id]').val(), 'target_cat_id': $('select[name=target_cat_id]').val()},
                        url:"/category/moved",
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