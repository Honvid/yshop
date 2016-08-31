<?php
/**
 * Created by PhpStorm.
 * User: Honvid
 * Date: 16/4/26
 * Time: 下午3:01
 */
use admin\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

$this->title = $title;
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
                    <a href="/menu/index" class="btn btn-default" title="菜单列表"><span class="fa fa-reply"></span> 菜单列表</a>
                </div>
            </div>
            <div class="box-body">
                <form class="form" id="edit-menu">
                    <div class="col-sm-8 form-group">
                        <label for="menu_name" class="col-sm-2 control-label">名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="menu_name" id="menu_name" placeholder="菜单名称" value="<?php echo !empty($menu) ? $menu['menu_name'] : '';?>">
                            <input type="hidden" name="menu_id" id="menu_id" value="<?php echo !empty($menu) ? $menu['menu_id'] : 0;?>">
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="menu_rule" class="col-sm-2 control-label">路由规则</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" id="menu_rule" name="menu_rule[]" multiple>
                                <option value="0">请选择路由</option>
                                <?php foreach ($route as $key => $value) { ?>
                                    <?php if(!empty($menu['menu_rule'])) {
                                        $route_list = explode(',', $menu['menu_rule']);
                                        foreach($route_list as $r){ ?>
                                            <option value="<?php echo $value['route']; ?>"<?php echo $r == $value['route'] ? ' selected' : '';?>><?php echo '['.$value['module'].'] - '.'['.$value['title'].'] - '.$value['desc'] . ' - ' . $value['route']; ?></option>
                                    <?php } }else{ ?>
                                    <option value="<?php echo $value['route']; ?>"><?php echo '['.$value['module'].'] - '.'['.$value['title'].'] - '.$value['desc'] . ' - ' . $value['route']; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="parent_id" class="col-sm-2 control-label">上级菜单</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" id="parent_id" name="parent_id">
                                <option value="0">顶级菜单</option>
                                <?php foreach ($list as $key => $value) { ?>
                                    <?php
                                        $icon = '';
                                        if($value['parent_id'] == 0){
                                            $icon = "◎ ";
                                        }else {
                                            if ($value['level'] == 2) {
                                                $icon = str_repeat("&nbsp;", $value['level'] * 3) . '⊙';
                                            } elseif($value['level'] == 3) {
                                                $icon = str_repeat("&nbsp;", $value['level'] * 4) . '●';
                                            } elseif($value['level'] == 4) {
                                                $icon = str_repeat("&nbsp;", $value['level'] * 4) . '○';
                                            }
                                        }
                                    ?>
                                    <option value="<?php echo $value['menu_id']; ?>"<?php echo !empty($menu) && $value['menu_id'] == $menu['parent_id'] ? ' selected' : '';?>><?php echo $icon . ' ' . $value['menu_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="icon" class="col-sm-2 control-label">图标</label>
                        <div class="col-sm-10">
                            <?php if(!empty($menu['icon'])) { ?>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="<?php echo $menu['icon']; ?>"></i></span>
                                <input type="text" class="form-control" name="icon" id="icon" placeholder="图标" value="<?php echo !empty($menu) ? $menu['icon'] : '';?>">
                            </div>
                            <?php } else { ?>
                            <input type="text" class="form-control" name="icon" id="icon" placeholder="图标" value="<?php echo !empty($menu) ? $menu['icon'] : '';?>">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="desc" class="col-sm-2 control-label">菜单描述</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="5" name="desc" id="desc"><?php echo !empty($menu) ? $menu['desc'] : '';?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="sort" class="col-sm-2 control-label">排序</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="sort" id="sort" placeholder="排序" value="<?php echo !empty($menu) ? $menu['sort'] : '';?>">
                        </div>
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="is_show" class="col-sm-2 control-label">是否菜单显示</label>
                        <div class="col-sm-10">
                            <div class="radio" style="margin-top: 5px;">
                                <label><input type="radio" name="is_show" id="is_show" value="1" <?php echo !empty($menu) && ($menu['is_show'] == 1) ? 'checked' : '';?>>是</label>
                                <label style="padding-left: 40px;"><input type="radio" name="is_show" value="0" <?php echo empty($menu) || ($menu['is_show'] == 0) ? 'checked' : '';?>>否</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <div class="col-sm-8">
                    <div class="pull-left">
                        <a href="/menu/index" class="btn btn-default">取消</a>
                        <a href="javascript:;" class="btn btn-info menu-edit">保存</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php AppAsset::addJs($this,'@web/js/jquery.form.js'); ?>
<?php $this->beginBlock('script'); ?>
    <script>
        $('.select2').select2();
        $(".menu-edit").click(function(){
            if($("#menu_name").val() == ""){
                swal({"title":"菜单名称必须填写","confirmButtonText":"确定", "type":"warning"});
                return false;
            }
            $("#edit-menu").ajaxSubmit({
                type: "post",
                url:"/menu/update",
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
        });
    </script>
<?php $this->endBlock(); ?>