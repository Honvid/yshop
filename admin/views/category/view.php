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
                                <a href="/category/index" class="btn btn-default" title="分类列表"><span class="fa fa-reply"></span> 分类列表</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <form class="form" id="edit-category" enctype="multipart/form-data">
                                <div class="col-sm-8 form-group">
                                    <label for="cat_name" class="col-sm-3 control-label text-right">分类名称</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="cat_name" id="cat_name" placeholder="分类名称" value="<?php echo !empty($category) ? $category['cat_name'] : '';?>">
                                    </div>
                                    <input type="hidden" name="cat_id" value="<?php echo !empty($category) ? $category['cat_id'] : 0;?>">
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="parent_id" class="col-sm-3 control-label text-right">上级分类</label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2" id="parent_id" name="parent_id">
                                            <option value="0">顶级分类</option>
                                            <?php foreach ($category_list as $key => $value) {
                                                $icon = '';
                                                if($value['parent_id'] == 0){
                                                    $icon = "◎ ";
                                                }else {
                                                    if ($value['level'] == 1) {
                                                        $icon = str_repeat("&nbsp;", $value['level'] * 6) . '⊙';
                                                    } elseif($value['level'] == 2) {
                                                        $icon = str_repeat("&nbsp;", $value['level'] * 5) . '●';
                                                    } elseif($value['level'] == 3) {
                                                        $icon = str_repeat("&nbsp;", $value['level'] * 4) . '○';
                                                    }
                                                }
                                            ?><option value="<?php echo $value['cat_id']; ?>"<?php echo !empty($category) && $value['cat_id'] == $category['parent_id'] ? " selected" : ''; ?>><?php echo $icon . ' ' .$value['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="measure_unit" class="col-sm-3 control-label text-right">数量单位</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="measure_unit" id="measure_unit" placeholder="数量单位" value="<?php echo !empty($category) ? $category['measure_unit'] : '';?>">
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="sort_order" class="col-sm-3 control-label text-right">排序</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="sort_order" id="sort_order" placeholder="排序" value="<?php echo !empty($category) ? $category['sort_order'] : '';?>">
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="is_show" class="col-sm-3 control-label text-right">是否显示</label>
                                    <div class="col-sm-9">
                                        <div class="radio" style="margin-top: 5px;">
                                            <label><input type="radio" name="is_show" id="is_show" value="1" <?php echo !empty($category) && ($category['is_show'] == 1) ? 'checked' : '';?>>是</label>
                                            <label style="padding-left: 40px;"><input type="radio" name="is_show" value="0" <?php echo !empty($category) && ($category['is_show'] == 0) ? 'checked' : '';?>>否</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="show_in_nav" class="col-sm-3 control-label text-right">是否显示在导航栏</label>
                                    <div class="col-sm-9">
                                        <div class="radio" style="margin-top: 5px;">
                                            <label><input type="radio" name="show_in_nav" id="show_in_nav" value="1" <?php echo !empty($category) && ($category['show_in_nav'] == 1) ? 'checked' : '';?>>是</label>
                                            <label style="padding-left: 40px;"><input type="radio" name="show_in_nav" value="0" <?php echo !empty($category) && ($category['show_in_nav'] == 0) ? 'checked' : '';?>>否</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="cat_recommend" class="col-sm-3 control-label text-right">设为首页推荐</label>
                                    <div class="col-sm-9">
                                        <div class="checkbox">
                                            <label style="padding: 0 20px;"><input type="checkbox" name="cat_recommend[]" id="cat_recommend" value="1" <?php echo !empty($cat_recommend[1]) && ($cat_recommend[1] == 1) ? 'checked' : '';?>>精品</label>
                                            <label style="padding: 0 20px;"><input type="checkbox" name="cat_recommend[]" value="2" <?php echo !empty($cat_recommend[2]) && ($cat_recommend[2] == 1) ? 'checked' : '';?>>新品</label>
                                            <label style="padding: 0 20px;"><input type="checkbox" name="cat_recommend[]" value="3" <?php echo !empty($cat_recommend[3]) && ($cat_recommend[3] == 1) ? 'checked' : '';?>>热销</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label class="col-sm-3 control-label text-right">筛选属性</label>
                                    <div class="col-sm-9">
                                    <?php if(!empty($attr_list)) foreach ($attr_list as $k => $v) { ?>
                                        <div class="attr-group">
                                            <div class="col-sm-4" style="padding-left: 0;">
                                                <select class="form-control select2 type_list" style="position: absolute;">
                                                    <option value="0">请选择</option>
                                                    <?php foreach ($type_list as $key => $value) { ?>
                                                        <option value="<?php echo $value['cat_id']; ?>" <?php echo ($v['type_id'] == $value['cat_id']) ? 'selected' : ''; ?>>
                                                            <?php
                                                                echo $value['cat_name']
                                                            ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control select2 filter_attr" name="filter_attr[]" style="position: absolute;">
                                                    <option value="0">请选择</option>
                                                    <?php foreach ($v['attr_list'] as $attr) { ?>
                                                        <option value="<?php echo $attr['attr_id']; ?>" <?php echo ($v['attr_id'] == $attr['attr_id']) ? 'selected' : ''; ?>>
                                                            <?php
                                                                echo $attr['attr_name']
                                                            ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <span class="input-group-btn">
                                                <?php if($k == 0){ ?>
                                                    <a href="javascript:;" class="btn btn-info btn-flat attr-add"><span class="fa fa-plus"></span></a>
                                                <?php }else { ?>
                                                    <a href="javascript:;" class="btn btn-warning btn-flat attr-delete"><span class="fa fa-remove"></span></a>
                                                <?php } ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php }else{ ?>
                                        <div class="attr-group">
                                            <div class="col-sm-4" style="padding-left: 0;">
                                                <select class="form-control select2 type_list" style="position: absolute;">
                                                    <option value="0">请选择</option>
                                                    <?php foreach ($type_list as $key => $value) { ?>
                                                        <option value="<?php echo $value['cat_id']; ?>">
                                                            <?php
                                                                echo $value['cat_name']
                                                            ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control select2 filter_attr" name="filter_attr[]" style="position: absolute;">
                                                    <option value="0">请选择</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <span class="input-group-btn">
                                                    <a href="javascript:;" class="btn btn-info btn-flat attr-add"><span class="fa fa-plus"></span></a>
                                                </span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                        <label>筛选属性可在前分类页面筛选商品</label>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="grade" class="col-sm-3 control-label text-right">价格区间个数</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="grade" id="grade" placeholder="价格区间个数" value="<?php echo !empty($category) ? $category['grade'] : 0;?>">
                                        <label>该选项表示该分类下商品最低价与最高价之间的划分的等级个数，填0表示不做分级，最多不能超过10个。</label>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="template_file" class="col-sm-3 control-label text-right">分类的样式表文件</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="template_file" id="template_file" placeholder="分类的样式表文件" value="<?php echo !empty($category) ? $category['template_file'] : '';?>">
                                        <label>您可以为每一个商品分类指定一个样式表文件。例如文件存放在 themes 目录下则输入：themes/style.css</label>
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="keywords" class="col-sm-3 control-label text-right">关键字</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="keywords" id="keywords" placeholder="用空格分隔" value="<?php echo !empty($category) ? $category['keywords'] : '';?>">
                                    </div>
                                </div>
                                <div class="col-sm-8 form-group">
                                    <label for="cat_desc" class="col-sm-3 control-label text-right">分类描述</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" rows="5" name="cat_desc" id="cat_desc"><?php echo !empty($category) ? $category['cat_desc'] : '';?></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-8">
                                <div class="pull-left">
                                    <a href="/category/index" class="btn btn-default">取消</a>
                                    <a href="javascript:;" class="btn btn-info category-edit">保存</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
<?php AppAsset::addJs($this,'@web/js/jquery.form.js'); ?>
<?php $this->beginBlock('script'); ?>
    <script>
        $(".select2").select2();
        $(".attr-add").click(function(){
            $(".attr-group:last").after($(".attr-group:first").clone());
            $(".attr-group:last").find(".btn-info").addClass("btn-warning").removeClass("btn-info");
            $(".attr-group:last").find(".attr-add").addClass("attr-delete").removeClass("attr-add");
            $(".attr-group:last").find(".fa-plus").addClass("fa-remove").removeClass("fa-plus");
            $(".attr-group:last").find(".form-control").removeClass("select2-hidden-accessible").removeAttr("tabindex").removeAttr("aria-hidden");
            $(".attr-group:last").find(".filter_attr option").remove();
            $(".attr-group:last").find(".select2-container").remove();
            $(".select2").select2();
        });
        $(document).on("click", ".attr-delete", function(){
            $(this).parent().parent().parent().remove();
        });
        $(document).on("change",".type_list",function(){
            var $this = $(this);
            var cat_id = $this.children("option:selected").val();
            $.ajax({
                url:"/category/search",
                data:{"cat_id":cat_id},
                type:"jsonp",
                success:function(data){
                    if(data.code == 1){
                        var option = "<option value=\"0\">请选择</option>\n";
                        for(var i=0;i<data.data.length;i++){
                            option += "<option value=\""+ data.data[i].attr_id +"\">"+data.data[i].attr_name+"</option>\n";
                        }
                        var next = $this.parent().next().find(".filter_attr");
                        next.find("option").remove();
                        next.append(option);
                        $(".select2").select2();
                    }
                },
                error:function(){
                    swal({"title":"请求失败，请刷新后重试！","confirmButtonText":"确定", "type":"error"});
                }
            });
        });
        $(".category-edit").click(function(){
            if($("#cat_name").val() == ""){
                swal({"title":"品牌名称必须填写","confirmButtonText":"确定", "type":"warning"});
                return false;
            }
            $("#edit-category").ajaxSubmit({
                type: "post",
                url:"/category/update",
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
        });
    </script>
<?php $this->endBlock(); ?>