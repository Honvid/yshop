<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Html;
use common\vendor\bootstrapfileinput\FileInputAsset;
use common\vendor\icheck\ICheckAsset;

AppAsset::register($this);
FileInputAsset::register($this);
ICheckAsset::register($this);

$this->title = $title;
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
                                <a href="/brand/index" class="btn btn-default" title="品牌列表"><span class="fa fa-reply"></span> 品牌列表</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <form class="form" id="edit-brand" enctype="multipart/form-data">
                                <div class="col-sm-8 form-group">
                                    <label for="brand_name" class="col-sm-2 control-label">品牌名称</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="品牌名称" value="<?php echo !empty($brand) ? $brand['brand_name'] : '';?>">
                                    </div>
                                    <input type="hidden" name="brand_id" value="<?php echo !empty($brand) ? $brand['brand_id'] : 0;?>">
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="site_url" class="col-sm-2 control-label">品牌官网</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="site_url" id="site_url" placeholder="品牌官网" value="<?php echo !empty($brand) ? $brand['site_url'] : '';?>">
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="brand_logo" class="col-sm-2 control-label">品牌LOGO</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control file-loading" name="brand_logo" accept="image/*" id="brand_logo" multiple>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="brand_desc" class="col-sm-2 control-label">品牌描述</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" rows="5" name="brand_desc" id="brand_desc"><?php echo !empty($brand) ? $brand['brand_desc'] : '';?></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="sort_order" class="col-sm-2 control-label">排序</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="sort_order" id="sort_order" placeholder="排序" value="<?php echo !empty($brand) ? $brand['sort_order'] : '';?>">
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="is_show" class="col-sm-2 control-label">是否显示</label>
                                    <div class="col-sm-10">
                                        <div class="radio" style="margin-top: 5px;">
                                            <label><input type="radio" name="is_show" id="is_show" value="1" <?php echo !empty($brand) && ($brand['is_show'] == 1) ? 'checked' : '';?>>是</label>
                                            <label style="padding-left: 40px;"><input type="radio" name="is_show" value="0" <?php echo !empty($brand) && ($brand['is_show'] == 0) ? 'checked' : '';?>>否</label>
                                            <label style="padding: 10px 0;">(当品牌下还没有商品的时候，首页及分类页的品牌区将不会显示该品牌。)</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-8">
                                <div class="pull-left">
                                    <a href="/brand/index" class="btn btn-default">取消</a>
                                    <a href="javascript:;" class="btn btn-info brand-edit">保存</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
<?php
AppAsset::addJs($this,'@web/js/jquery.form.js');
?>
<?php $this->beginBlock('script'); ?>
    <script>
        $(".brand-edit").click(function(){
            if($("#brand_name").val() == ""){
                swal({"title":"品牌名称必须填写","confirmButtonText":"确定", "type":"warning"});
                return false;
            };
            $("#edit-brand").ajaxSubmit({
                type: "post",
                url:"/brand/update",
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
        });
        $("#brand_logo").fileinput({
            language: "zh",
            <?php
                if(!empty($brand['brand_logo'])){
                    echo 'initialPreview:[\''.'<img src="'.$brand["brand_logo"] .'" class="file-preview-image">\'],' ."\n";
                    echo 'initialCaption:"' . $brand["brand_logo"] . '",' . "\n";
                }
            ?>
            overwriteInitial: true,
            showUpload: false,
            previewFileType: "image",
            browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
            removeClass: "btn btn-danger",
            removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
            allowedFileTypes: ["image"],
            maxFileCount: 1
        });
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });
    </script>
<?php $this->endBlock(); ?>