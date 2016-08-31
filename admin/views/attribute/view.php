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
                    <a href="/attribute/index?id=<?php echo !empty($attr) ? $attr['cat_id'] : 0 ?>" class="btn btn-default" title="属性管理"><span class="fa fa-reply"></span> 属性管理</a>
                </div>
            </div>
            <div class="box-body">
                <form class="form" id="edit-attribute">
                    <div class="col-sm-10 form-group">
                        <label for="attr_name" class="col-sm-3 control-label">属性名称</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="attr_name" id="attr_name" placeholder="属性名称" value="<?php echo !empty($attr) ? $attr['attr_name'] : '';?>">
                        </div>
                        <input type="hidden" name="attr_id" value="<?php echo !empty($attr) ? $attr['attr_id'] : 0;?>">
                        <input type="hidden" name="ids" value="<?php echo !empty($attr) ? $attr['cat_id'] : 0;?>">
                        <input type="hidden" name="attr_group_id" value="<?php echo !empty($attr) ? $attr['attr_group'] : -1;?>">
                    </div>
                    <div class="col-sm-10 form-group">
                        <label for="cat_id" class="col-sm-3 control-label">所属商品类型</label>
                        <div class="col-sm-9">
                            <select class="form-control select2" id="cat_id" name="cat_id">
                                <option value="0">请选择</option>
                                <?php foreach ($type_list as $key => $value) { ?>
                                    <option value="<?php echo $value['cat_id']; ?>"<?php echo !empty($attr) && $value['cat_id'] == $attr['cat_id'] ? ' selected':'';?>><?php echo $value['cat_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-10 form-group attr_group" <?php echo empty($group_list) ? "style=\"display: none;\"" : ''; ?>>
                        <label for="attr_group" class="col-sm-3 control-label">属性分组</label>
                        <div class="col-sm-9">
                            <select class="form-control select2" id="attr_group" name="attr_group">
                                <?php if(!empty($group_list)) foreach ($group_list as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo (!empty($attr) && $key == $attr['attr_group']) ? "selected" : ''; ?>><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-10 form-group">
                        <label for="attr_index" class="col-sm-3 control-label">能否进行检索</label>
                        <div class="col-sm-9">
                            <div>
                                <label><input style="margin: 0 5px;" type="radio" name="attr_index" id="attr_index" value="0" <?php echo empty($attr['attr_index']) || ($attr['attr_index'] == 0) ? 'checked' : '';?>>不需要检索</label>
                                <label style="padding-left: 10px;"><input style="margin: 0 5px;" type="radio" name="attr_index" value="1" <?php echo !empty($attr) && ($attr['attr_index'] == 1) ? 'checked' : '';?>>关键字检索</label>
                                <label style="padding-left: 10px;"><input style="margin: 0 5px;" type="radio" name="attr_index" value="2" <?php echo !empty($attr) && ($attr['attr_index'] == 2) ? 'checked' : '';?>>范围检索</label>
                                <label style="padding: 5px 0;">(不需要该属性成为检索商品条件的情况请选择不需要检索，需要该属性进行关键字检索商品时选择关键字检索，如果该属性检索时希望是指定某个范围时，选择范围检索。)</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10 form-group">
                        <label for="is_linked" class="col-sm-3 control-label">相同属性值的商品是否关联</label>
                        <div class="col-sm-9">
                            <div>
                                <label><input style="margin: 0 5px;" type="radio" name="is_linked" id="is_linked" value="1" <?php echo !empty($attr) && ($attr['is_linked'] == 1) ? 'checked' : '';?>>是</label>
                                <label style="padding-left: 10px;"><input style="margin: 0 5px;" type="radio" name="is_linked" value="0" <?php echo empty($attr['is_linked']) || ($attr['is_linked'] == 0) ? 'checked' : '';?>>否</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10 form-group">
                        <label for="attr_type" class="col-sm-3 control-label">属性是否可选</label>
                        <div class="col-sm-9">
                            <div>
                                <label><input style="margin: 0 5px;" type="radio" name="attr_type" id="attr_type" value="0" <?php echo empty($attr['attr_type']) || ($attr['attr_type'] == 0) ? 'checked' : '';?>>唯一属性</label>
                                <label style="padding-left: 10px;"><input style="margin: 0 5px;" type="radio" name="attr_type" value="1" <?php echo !empty($attr) && ($attr['attr_type'] == 1) ? 'checked' : '';?>>单选属性</label>
                                <label style="padding-left: 10px;"><input style="margin: 0 5px;" type="radio" name="attr_type" value="2" <?php echo !empty($attr) && ($attr['attr_type'] == 2) ? 'checked' : '';?>>复选属性</label>
                                <label style="padding: 5px 0;">(选择"单选/复选属性"时，可以对商品该属性设置多个值，同时还能对不同属性值指定不同的价格加价，用户购买商品时需要选定具体的属性值。选择"唯一属性"时，商品的该属性值只能设置一个值，用户只能查看该值。)</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10 form-group">
                        <label for="attr_input_type" class="col-sm-3 control-label">该属性值的录入方式</label>
                        <div class="col-sm-9">
                            <div>
                                <label><input style="margin: 0 5px;" type="radio" name="attr_input_type" id="attr_input_type" value="0" <?php echo !empty($attr) && ($attr['attr_input_type'] == 0) ? 'checked' : '';?>>手工录入</label>
                                <label style="padding-left: 10px;"><input style="margin: 0 5px;" type="radio" name="attr_input_type" value="1" <?php echo !empty($attr) && ($attr['attr_input_type'] == 1) ? 'checked' : '';?>>从下面的列表中选择（一行代表一个可选值）</label>
                                <label style="padding-left: 10px;"><input style="margin: 0 5px;" type="radio" name="attr_input_type" value="2" <?php echo !empty($attr) && ($attr['attr_input_type'] == 2) ? 'checked' : '';?>>多行文本框</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10 form-group">
                        <label for="attr_values" class="col-sm-3 control-label">属性分组</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" rows="5" name="attr_values" id="attr_values"><?php echo !empty($attr) ? str_replace(',', "\r\n", $attr['attr_values']) : '';?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <div class="col-sm-8">
                    <div class="pull-left">
                        <a href="/attribute/index" class="btn btn-default">取消</a>
                        <a href="javascript:;" class="btn btn-info attribute-edit">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php AppAsset::addJs($this,'@web/js/jquery.form.js'); ?>
<?php $this->beginBlock('script'); ?>
    <script>
        $(".attribute-edit").click(function(){
            if($("#attr_name").val() == ""){
                swal({"title":"属性名称必须填写","confirmButtonText":"确定", "type":"warning"});
                return false;
            };
            $("#edit-attribute").ajaxSubmit({
                type: "post",
                url:"/attribute/update",
                success: function (data) {
                    if(data.code == 1){
                        swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                            window.location.href = "/attribute/index?id=" + $("input[name=ids]").val();
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
        $(".select2").select2();
        $("select[name=cat_id]").change(function(){
            $.ajax({
                url:'/attribute/group',
                data:{"cat_id": $("#cat_id").find("option:selected").val()},
                dataType:"json",
                success: function (data) {
                    if(data.code == 1){
                        var group = $(".attr_group");
                        if(data.data.length > 0){
                            group.show();
                            group.find('select[name=attr_group]').children("option").remove();
                            $.each(data.data, function(i, value){
                                var options = "";
                                if(i == $("input[name=attr_group_id]").val()) {
                                    options += "<option value=\"" + i + "\" selected>" + value + "</option>";
                                }else{
                                    options += "<option value=\"" + i + "\">" + value + "</option>";
                                }
                                group.find('select[name=attr_group]').append(options);
                                group.find('select[name=attr_group]').select2();
                            });
                        }else{
                            group.hide();
                            group.find('select[name=attr_group]').children("option").remove();
                        }
                    }
                },
                error: function () {
                    swal({"title":"请求失败，请刷新后重试！","confirmButtonText":"确定", "type":"error"});
                }
            });
        });
    </script>
<?php $this->endBlock(); ?>