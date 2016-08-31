<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use common\vendor\daterangepicker\DateRangePicker;
use common\vendor\ueditor\UEditor;
use common\vendor\bootstrapfileinput\FileInputAsset;

AppAsset::register($this);
FileInputAsset::register($this);
$this->registerCssFile('/js/colorbox/colorbox.css');
$this->title = $title;
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
                                <a href="/goods/index" class="btn btn-default" title="商品列表"><span class="fa fa-reply"></span> 商品列表</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#goods-base" data-toggle="tab">通用信息</a></li>
                                            <li><a href="#goods-more" data-toggle="tab">详细描述</a></li>
                                            <li><a href="#goods-other" data-toggle="tab">其他信息</a></li>
                                            <li><a href="#goods-type" data-toggle="tab">商品属性</a></li>
                                            <li><a href="#goods-photo" data-toggle="tab">商品相册</a></li>
                                            <li><a href="#goods-relation" data-toggle="tab">关联商品</a></li>
                                            <li><a href="#goods-parts" data-toggle="tab">配件</a></li>
                                            <li><a href="#goods-article" data-toggle="tab">相关文章</a></li>
                                        </ul>
                                        <form class="form" id="edit-product" enctype="multipart/form-data">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="goods-base">
                                                    <div class="box-body">
                                                        <div class="col-sm-8 form-group">
                                                            <label for="name" class="col-sm-2 control-label">商品名称</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" name="name" id="name" placeholder="商品名称" value="<?php echo !empty($goods) ? $goods['goods_name'] : '';?>">
                                                            </div>
                                                            <input type="hidden" name="goods_id" value="<?php echo !empty($goods) ? $goods['goods_id'] : '';?>">
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="sn" class="col-sm-2 control-label">商品货号</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" name="sn" id="sn" placeholder="如果您不输入商品货号，系统将自动生成一个唯一的货号。" value="<?php echo !empty($goods) ? $goods['goods_sn'] : '';?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="cat_id" class="col-sm-2 control-label">商品分类</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <select class="form-control select2" id="cat_id" name="cat_id" style="position: absolute;">
                                                                        <option value="0">请选择</option>
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
                                                                        ?>
                                                                            <option value="<?php echo $value['cat_id']; ?>"<?php echo !empty($goods) && $value['cat_id'] == $goods['cat_id'] ? " selected" : ''; ?>><?php echo $icon . ' ' .$value['name']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <span class="input-group-btn">
                                                                        <a href="/category/view" class="btn btn-info btn-flat">添加分类</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="other-btn" class="col-sm-2 control-label">扩展分类</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <span class="input-group-btn" id="other-btn">
                                                                        <a href="javascript:;" class="btn btn-info btn-flat">添加</a>
                                                                    </span>
                                                                    <?php if(!empty($goods_cats)) foreach($goods_cats as $cat){ ?>
                                                                        <div class="col-sm-4">
                                                                            <select class="form-control select2" name="other_cat[]" style="position: absolute;">
                                                                                <option value="0">请选择</option>
                                                                                <?php foreach ($category_list as $key => $value) { ?>
                                                                                    <option value="<?php echo $value['cat_id']; ?>"
                                                                                        <?php
                                                                                        if(!empty($goods)){
                                                                                            if($value['cat_id'] == $cat['cat_id']){
                                                                                                echo "selected";
                                                                                            }
                                                                                        }
                                                                                        ?>>
                                                                                        <?php
                                                                                        echo str_repeat("--", $value['level']);
                                                                                        echo $value['name']
                                                                                        ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="brand_id" class="col-sm-2 control-label">商品品牌</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <select class="form-control select2" id="brand_id" name="brand_id" style="position: absolute;">
                                                                        <option value="0">请选择</option>
                                                                        <?php foreach ($brand_list as $key => $value) { ?>
                                                                            <option value="<?php echo $value['brand_id']; ?>"
                                                                            <?php
                                                                                if(!empty($goods)){
                                                                                    if($value['brand_id'] == $goods['brand_id']){
                                                                                        echo "selected";
                                                                                    }
                                                                                }
                                                                            ?>>
                                                                                <?php
                                                                                    echo $value['brand_name']
                                                                                ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <span class="input-group-btn">
                                                                        <a href="/brand/view" class="btn btn-info btn-flat">添加品牌</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="suppliers_id" class="col-sm-2 control-label">选择供应商</label>
                                                            <div class="col-sm-10">
                                                                <select class="form-control select2" id="suppliers_id" name="suppliers_id">
                                                                    <option value="0">不指定供货商属于本店商品</option>
                                                                    <?php foreach ($suppliers_list as $key => $value) { ?>
                                                                        <option value="<?php echo $value['suppliers_id']; ?>"
                                                                            <?php
                                                                                if(!empty($goods)){
                                                                                    if($value['suppliers_id'] == $goods['suppliers_id']){
                                                                                        echo "selected";
                                                                                    }
                                                                                }
                                                                            ?>>
                                                                            <?php
                                                                                echo $value['suppliers_name']
                                                                            ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="shop_price" class="col-sm-2 control-label">本店售价</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" name="shop_price" id="shop_price" placeholder="0" value="<?php echo !empty($goods) ? $goods['shop_price'] : 0;?>">
                                                                    <span class="input-group-btn">
                                                                        <a href="javascript:;" class="btn btn-info btn-flat">按市场价计算</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="rank_1" class="col-sm-2 control-label">会员价格</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <?php foreach ($rank_list as $key => $value) { ?>
                                                                    <span class="input-group-addon" style="border: 0;padding: 0 5px;"><?php echo $value['rank_name']; ?></span>
                                                                    <input type="number" id="rank_<?php echo $value['rank_id'];?>" name="user_price[]" class="form-control" value="<?php echo !empty($rank_price[$value['rank_id']]) ? $rank_price[$value['rank_id']] : -1; ?>">
                                                                    <input type="hidden" name="user_rank[]" value="<?php echo $value['rank_id'];?>" />
                                                                    <?php } ?>
                                                                </div>
                                                                <label>会员价格为-1时表示会员价格按会员等级折扣率计算。你也可以为每个等级指定一个固定价格</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="volume_0" class="col-sm-2 control-label">优惠价格</label>
                                                            <div class="col-sm-10">
                                                                <?php if(!empty($volume_price)){ foreach($volume_price as $k => $value) { ?>
                                                                <div class="input-group volume-group"<?php echo $k != 0 ? 'style="margin: 10px 0;"' : ''; ?>>
                                                                    <span class="input-group-addon" style="border: 0;padding: 0 5px;">优惠数量</span>
                                                                    <input type="number" id="volume_<?php echo $k;?>" name="volume_number[]" class="form-control" value="<?php echo $value['volume_number']; ?>">
                                                                    <span class="input-group-addon" style="border: 0;padding: 0 5px;">优惠价格</span>
                                                                    <input type="number" name="volume_price[]" class="form-control" value="<?php echo $value['volume_price']; ?>">
                                                                    <span class="input-group-btn">
                                                                        <?php if($k == 0) { ?>
                                                                            <a href="javascript:;" class="btn btn-info btn-flat add-volume"><span class="fa fa-plus"></span></a>
                                                                        <?php } else { ?>
                                                                            <a href="javascript:;" class="btn btn-warning btn-flat remove-volume"><span class="fa fa-remove"></span></a>
                                                                        <?php } ?>
                                                                    </span>
                                                                </div>
                                                                <?php } } else { ?>
                                                                <div class="input-group volume-group">
                                                                    <span class="input-group-addon" style="border: 0;padding: 0 5px;">优惠数量</span>
                                                                    <input type="number" id="volume_0" name="volume_number[]" class="form-control" placeholder="享受优惠需购买数量">
                                                                    <span class="input-group-addon" style="border: 0;padding: 0 5px;">优惠价格</span>
                                                                    <input type="number" name="volume_price[]" class="form-control" placeholder="达到数量时的优惠价格">
                                                                    <span class="input-group-btn">
                                                                        <a href="javascript:;" class="btn btn-info btn-flat add-volume"><span class="fa fa-plus"></span></a>
                                                                    </span>
                                                                </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="market_price" class="col-sm-2 control-label">市场售价</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control" name="market_price" id="market_price" placeholder="0" value="<?php echo !empty($goods) ? $goods['market_price'] : 0;?>">
                                                                    <span class="input-group-btn">
                                                                        <a href="javascript:;" class="btn btn-info btn-flat">取整数</a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="give_integral" class="col-sm-2 control-label">消费积分</label>
                                                            <div class="col-sm-10">
                                                                <input type="number" class="form-control" name="give_integral" id="give_integral" value="<?php echo !empty($goods) ? $goods['give_integral'] : -1;?>">
                                                                <label>购买该商品时赠送消费积分数,-1表示按商品价格赠送</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="rank_integral" class="col-sm-2 control-label">等级积分</label>
                                                            <div class="col-sm-10">
                                                                <input type="number" class="form-control" name="rank_integral" id="rank_integral" value="<?php echo !empty($goods) ? $goods['rank_integral'] : -1;?>">
                                                                <label>购买该商品时赠送等级积分数,-1表示按商品价格赠送</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="integral" class="col-sm-2 control-label">积分价格</label>
                                                            <div class="col-sm-10">
                                                                <input type="number" class="form-control" name="integral" id="integral" placeholder="0" value="<?php echo !empty($goods) ? $goods['integral'] : 0;?>">
                                                                <label>(此处需填写金额)购买该商品时最多可以使用积分的金额</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="is_promote" class="col-sm-2 control-label">促销价</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <input type="checkbox" name="is_promote" id="is_promote" value="<?php echo !empty($goods) ? $goods['is_promote'] : 0;?>" <?php if(!empty($goods)) echo $goods['is_promote'] == 1 ? 'checked' : '';?>>
                                                                    </span>
                                                                    <input type="text" class="form-control" id="is_promote1" name="promote_price" value="<?php echo !empty($goods) ? $goods['promote_price'] : 0;?>" <?php if(!empty($goods)) echo $goods['is_promote'] == 1 ? 'enabled' : 'disabled';?>>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="promote_date" class="col-sm-2 control-label">促销日期</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <?php
                                                                        echo DateRangePicker::widget([
                                                                            'htmlOptions' => [
                                                                                'id'          => 'promote_date',
                                                                                'name'        => 'promote_date',
                                                                                'class'       => 'form-control',
                                                                                'placeholder' => '请选择促销时间段',
                                                                                'value'       => !empty($goods['promote_start_date'])? (date("Y/m/d", $goods['promote_start_date']) . ' - ' . date("Y/m/d", $goods['promote_end_date'])): '',
                                                                                'style'       => '',
                                                                                'disabled'    => !empty($goods['is_promote']) ? 'false' : 'disabled',
                                                                            ]
                                                                        ]);
                                                                    ?>
                                                                    <span class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="goods_img" class="col-sm-2 control-label">商品图片</label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <input type="radio" name="is_goods_img_web" value="0" checked>
                                                                    </span>
                                                                    <input type="file" class="form-control file-loading" id="goods_img" name="goods_img" accept="image/*" >
                                                                </div>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <input type="radio" name="is_goods_img_web" value="1">
                                                                    </span>
                                                                    <input type="text" class="form-control" id="goods_img_url" name="goods_img_url" placeholder="图片链接地址" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="goods_thumb" class="col-sm-2 control-label">
                                                                缩略图
                                                                <?php
                                                                    if(!empty($goods['goods_thumb'])){
                                                                        echo '<br /><a href="'.$goods['goods_thumb'].'" target="_bank">图片预览</a>';
                                                                    }
                                                                ?>
                                                            </label>
                                                            <div class="col-sm-10">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <input type="radio" name="is_goods_thumb_web" value="0" checked>
                                                                    </span>
                                                                    <input type="file" class="form-control" id="goods_thumb" name="goods_thumb">
                                                                </div>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <input type="radio" name="is_goods_thumb_web" value="1">
                                                                    </span>
                                                                    <input type="text" class="form-control" id="goods_thumb_url" name="goods_thumb_url" placeholder="图片链接地址" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="goods-more">
                                                    <div class="box-body">
                                                        <?php echo UEditor::widget([
                                                            'name' => 'goods_desc',
                                                            'value' => !empty($goods['goods_desc']) ? base64_decode($goods['goods_desc']) : '',
                                                        ]);?>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="goods-other">
                                                    <div class="box-body">
                                                        <div class="col-sm-8 form-group">
                                                            <label for="goods_weight" class="col-sm-4 control-label">商品重量</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" name="goods_weight" id="goods_weight" value="<?php echo !empty($goods) ? $goods['goods_weight'] : 0;?>">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <select class="form-control" name="weight_unit">
                                                                    <option value="1000">千克</option>
                                                                    <option value="1" selected>克</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="goods_number" class="col-sm-4 control-label">库存数量</label>
                                                            <div class="col-sm-8">
                                                                <input type="number" class="form-control" name="goods_number" id="goods_number" placeholder="0" value="<?php echo !empty($goods) ? $goods['goods_number'] : 1;?>">
                                                                <label>库存在商品为虚货或商品存在货品时为不可编辑状态，库存数值取决于其虚货数量或货品数量</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="warn_number" class="col-sm-4 control-label">库存预警</label>
                                                            <div class="col-sm-8">
                                                                <input type="number" class="form-control" name="warn_number" id="warn_number" placeholder="0" value="<?php echo !empty($goods) ? $goods['warn_number'] : 1;?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="is_best" class="col-sm-4 control-label">加入推荐</label>
                                                            <div class="col-sm-8">
                                                                <div class="checkbox">
                                                                    <label style="padding: 0 20px;"><input type="checkbox" name="is_best" id="is_best" <?php echo !empty($goods) && ($goods['is_best'] == 1) ? 'checked' : '';?>>精品</label>
                                                                    <label style="padding: 0 20px;"><input type="checkbox" name="is_new" id="is_new" <?php echo !empty($goods) && ($goods['is_new'] == 1) ? 'checked' : '';?>>新品</label>
                                                                    <label style="padding: 0 20px;"><input type="checkbox" name="is_hot" id="is_hot" <?php echo !empty($goods) && ($goods['is_hot'] == 1) ? 'checked' : '';?>>热销</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="is_on_sale" class="col-sm-4 control-label">上架</label>
                                                            <div class="col-sm-8">
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" name="is_on_sale" id="is_on_sale" <?php echo !empty($goods) && ($goods['is_on_sale'] == 1) ? 'checked' : '';?>>打勾表示允许销售，否则不允许销售。</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="is_alone_sale" class="col-sm-4 control-label">能否作为普通商品出售</label>
                                                            <div class="col-sm-8">
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" name="is_alone_sale" id="is_alone_sale" <?php echo !empty($goods) && ($goods['is_alone_sale'] == 1) ? 'checked' : '';?>>打勾表示能作为普通商品销售，否则只能作为配件或赠品销售。</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="is_shipping" class="col-sm-4 control-label">是否为免运费商品</label>
                                                            <div class="col-sm-8">
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox" name="is_shipping" id="is_shipping" <?php echo !empty($goods) && ($goods['is_shipping'] == 1) ? 'checked' : '';?>>打勾表示此商品不会产生运费花销，否则按照正常运费计算。</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="keywords" class="col-sm-4 control-label">关键词</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" name="keywords" id="keywords" placeholder="用空格分隔" value="<?php echo !empty($goods) ? $goods['keywords'] : '';?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="goods_brief" class="col-sm-4 control-label">商品简单描述</label>
                                                            <div class="col-sm-8">
                                                                <textarea class="form-control" rows="3" name="goods_brief" id="goods_brief"><?php echo !empty($goods) ? $goods['goods_brief'] : '';?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label for="seller_note" class="col-sm-4 control-label">商家备注</label>
                                                            <div class="col-sm-8">
                                                                <textarea class="form-control" rows="3" name="seller_note" id="seller_note"><?php echo !empty($goods) ? $goods['seller_note'] : '';?></textarea>
                                                                <label>仅供商家自己看的信息</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="goods-type">
                                                    <div class="box-header">
                                                        <div class="col-sm-12 container-fluid">
                                                            <div class="col-sm-8 form-group">
                                                                <label for="goods_type" class="col-sm-2 control-label">商品类型</label>
                                                                <div class="col-sm-10">
                                                                    <select class="form-control select2" id="goods_type" name="goods_type" style="position: absolute;">
                                                                        <option value="0" <?php echo empty($goods) ? 'selected' : ''?>>请选择</option>
                                                                        <?php foreach ($type_list as $key => $value) { ?>
                                                                            <option value="<?php echo $value['cat_id']; ?>"
                                                                                <?php
                                                                                if(!empty($goods)){
                                                                                    if($value['cat_id'] == $goods['goods_type']){
                                                                                        echo "selected";
                                                                                    }
                                                                                }
                                                                                ?>>
                                                                                <?php
                                                                                echo $value['cat_name']
                                                                                ?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <label>请选择商品的所属类型，进而完善此商品的属性</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="box-body"><?php echo !empty($attr_html) ? $attr_html : ''; ?>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="goods-photo">
                                                    <div class="box-header">
                                                        <div class="col-sm-12 container-fluid">
                                                            <ul class="gallery-list">
                                                                <li class="btn btn-app btn-upload-img">
                                                                    <a href="javascript:;" id="local_img" class="group-upload">
                                                                        <i class="fa fa-plus-circle"></i> 添加本地图片
                                                                    </a>
                                                                    <a href="javascript:;" id="web_img" class="group-upload">
                                                                        <i class="fa fa-plus-circle"></i> 添加网络图片
                                                                    </a>
                                                                </li>
                                                                <?php if(!empty($gallery)) {
                                                                    foreach ($gallery as $key => $value) { ?>
                                                                <li>
                                                                    <a href="<?php echo $value['img_url']; ?>" data-rel="colorbox">
                                                                        <img alt="<?php echo $value['img_desc']; ?>" class="150x150" src="<?php echo $value['img_url']; ?>" />
                                                                        <div class="text">
                                                                            <div class="inner"><?php echo $value['img_desc']; ?></div>
                                                                        </div>
                                                                    </a>
                                                                    <div class="tools tools-bottom">
                                                                        <!-- <a href="#"><i class="fa fa-pencil"></i></a> -->
                                                                        <a href="javascript:;" class="del-gallery" gid="<?php echo $value['goods_id']; ?>" iid="<?php echo $value['img_id']; ?>"><i class="fa fa-trash-o"></i></a>
                                                                    </div>
                                                                </li>
                                                                <?php }} ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="box-body" id="upload-img-body">
                                                        <div class="col-sm-8 form-group">
                                                            <label class="col-sm-2 control-label">本地图片</label>
                                                            <div class="col-sm-10">
                                                                <input type="file" class="form-control" name="img_file[]">
                                                                <input class="form-control" type="text" name="img_file_desc[]" placeholder="图片描述">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 form-group">
                                                            <label class="col-sm-2 control-label">网络图片</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" name="img_url[]" placeholder="网络图片url地址">
                                                                <input class="form-control" type="text" name="img_url_desc[]" placeholder="图片描述">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="goods-relation">
                                                    <div class="box-header">
                                                        <div class="col-sm-8">
                                                            <div class="col-sm-4">
                                                                <label for="cat_id2" class="control-label">商品类型</label>
                                                                <select class="form-control select2" id="cat_id2" name="cat_id2" style="position: absolute;">
                                                                    <option value="0">请选择</option>
                                                                    <?php foreach ($category_list as $key => $value) {
                                                                        $icon = '';
                                                                        if($value['parent_id'] == 0){
                                                                            $icon = "◎ ";
                                                                        }else {
                                                                            if ($value['level'] == 1) {
                                                                                $icon = str_repeat("&nbsp;", $value['level'] * 4) . '⊙';
                                                                            } elseif($value['level'] == 2) {
                                                                                $icon = str_repeat("&nbsp;", $value['level'] * 5) . '●';
                                                                            } elseif($value['level'] == 3) {
                                                                                $icon = str_repeat("&nbsp;", $value['level'] * 6) . '○';
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $value['cat_id']; ?>"><?php echo $icon . ' ' .$value['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label for="brand_id2" class="control-label">商品品牌</label>
                                                                <select class="form-control select2" id="brand_id2" name="brand_id2" style="position: absolute;">
                                                                    <option value="0">请选择</option>
                                                                    <?php foreach ($brand_list as $key => $value) { ?>
                                                                        <option value="<?php echo $value['brand_id']; ?>">
                                                                            <?php
                                                                                echo $value['brand_name']
                                                                            ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label for="keyword2" class="control-label">关键词</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="keyword2" id="keyword2" class="form-control" placeholder="请输入关键词...">
                                                                    <span class="input-group-btn">
                                                                        <button type="submit" name="search" id="search-goods" class="btn btn-flat"><i class="fa fa-search"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>可选择商品</label>
                                                                <select multiple class="form-control" id="source_goods1" style="height: 250px;">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="text-center" style="display: block;margin-top: 10%;">操作</label>
                                                                <div class="form-group text-center">
                                                                    <div class="radio">
                                                                        <label>
                                                                            <input type="radio" name="is_double" value="0" checked>单向关联
                                                                        </label>
                                                                    </div>
                                                                    <div class="radio">
                                                                        <label>
                                                                            <input type="radio" name="is_double" value="1" >双向关联
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="btn-group-vertical" style="margin-left: 45%;">
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="全部选定" data-placement="right" id="addAll">
                                                                        <i class="fa fa-forward"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="选择" data-placement="right" id="add">
                                                                        <i class="fa fa-chevron-right"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="取消" data-placement="right" id="cancel">
                                                                        <i class="fa fa-chevron-left"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="全部取消" data-placement="right" id="cancelAll">
                                                                        <i class="fa fa-backward"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>已选择商品</label>
                                                                <select multiple class="form-control" id="source_select1" name="source_select1" style="height: 250px;">
                                                                <?php if(!empty($link_goods)) echo $link_goods; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="goods-parts">
                                                    <div class="box-header">
                                                        <div class="col-sm-8">
                                                            <div class="col-sm-4">
                                                                <label for="cat_id3" class="control-label">商品类型</label>
                                                                <select class="form-control select2" id="cat_id3" name="cat_id3" style="position: absolute;">
                                                                    <option value="0">请选择</option>
                                                                    <?php foreach ($category_list as $key => $value) {
                                                                        $icon = '';
                                                                        if($value['parent_id'] == 0){
                                                                            $icon = "◎ ";
                                                                        }else {
                                                                            if ($value['level'] == 1) {
                                                                                $icon = str_repeat("&nbsp;", $value['level'] * 4) . '⊙';
                                                                            } elseif($value['level'] == 2) {
                                                                                $icon = str_repeat("&nbsp;", $value['level'] * 5) . '●';
                                                                            } elseif($value['level'] == 3) {
                                                                                $icon = str_repeat("&nbsp;", $value['level'] * 6) . '○';
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $value['cat_id']; ?>"><?php echo $icon . ' ' .$value['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label for="brand_id3" class="control-label">商品品牌</label>
                                                                <select class="form-control select2" id="brand_id3" name="brand_id3" style="position: absolute;">
                                                                    <option value="0">请选择</option>
                                                                    <?php foreach ($brand_list as $key => $value) { ?>
                                                                        <option value="<?php echo $value['brand_id']; ?>">
                                                                            <?php
                                                                                echo $value['brand_name']
                                                                            ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label for="keyword3" class="control-label">关键词</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="keyword3" id="keyword3" class="form-control" placeholder="请输入关键词...">
                                                                    <span class="input-group-btn">
                                                                        <button type="submit" name="search" id="search-parts" class="btn btn-flat"><i class="fa fa-search"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>可选择商品(一次只能添加一个配件)</label>
                                                                <select multiple class="form-control" id="source_goods2" style="height: 250px;">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="text-center" style="display: block;margin: 9% 0;">操作</label>
                                                                <div class="col-sm-8 form-group" style="margin-left: 15%;">
                                                                    <label for="price2" class="col-sm-3 control-label" style="padding: 0;">价格</label>
                                                                    <div class="col-sm-8" style="padding: 0;">
                                                                        <input type="number" class="form-control" name="price2" id="price2" placeholder="0">
                                                                    </div>
                                                                </div>
                                                                <div class="btn-group-vertical" style="margin-left: 45%;">
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="选择" data-placement="right" id="add-price">
                                                                        <i class="fa fa-chevron-right"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="取消" data-placement="right" id="cancel-price">
                                                                        <i class="fa fa-chevron-left"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="全部取消" data-placement="right" id="cancel-all">
                                                                        <i class="fa fa-backward"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label>已选择商品</label>
                                                                <select multiple class="form-control" id="source_select2" name="source_select2" style="height: 250px;">
                                                                <?php if(!empty($groups_goods)) echo $groups_goods; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="goods-article">
                                                    <div class="box-header">
                                                        <div class="col-sm-6 form-group">
                                                            <label for="warn_number" class="col-sm-3 control-label">文章标题</label>
                                                            <div class="col-sm-9">
                                                                <div class="input-group">
                                                                    <input type="text" name="article_title" id="article_title" class="form-control" placeholder="请输入文章标题...">
                                                                    <span class="input-group-btn">
                                                                        <button type="submit" name="search" id="search-article" class="btn btn-flat"><i class="fa fa-search"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label style="display: block;" class="text-center">可选择文章</label>
                                                                <select multiple class="form-control" id="source_article" style="height: 250px;">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label class="text-center" style="display: block;margin: 9% 0;">操作</label>
                                                                <div class="btn-group-vertical" style="margin-left: 45%;">
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="全部选定" data-placement="right" id="add-all-article">
                                                                        <i class="fa fa-forward"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="选择" data-placement="right" id="add-article">
                                                                        <i class="fa fa-chevron-right"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="取消" data-placement="right" id="cancel-article">
                                                                        <i class="fa fa-chevron-left"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-original-title="全部取消" data-placement="right" id="cancel-all-article">
                                                                        <i class="fa fa-backward"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label style="display: block;" class="text-center">跟该商品关联的文章</label>
                                                                <select multiple class="form-control" id="select-article" name="select-article" style="height: 250px;">
                                                                <?php if(!empty($link_articles)) echo $link_articles; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.tab-content -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-8">
                                <div class="pull-left">
                                    <a href="/goods/index" class="btn btn-default">取消</a>
                                    <a href="javascript:;" class="btn btn-info product-edit">保存</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
<?php
    AppAsset::addJs($this,'@web/js/colorbox/jquery.colorbox-min.js');
    AppAsset::addJs($this,'@web/js/jquery.form.js');
    AppAsset::addJs($this,'@web/js/jqthumb/dist/jqthumb.min.js');
?>
<?php $this->beginBlock('script'); ?>
    <script>
        $(".select2").select2();
        $(".150x150").jqthumb({
            width : 150,
            height : 150
        });
        $("#goods_img").fileinput({
            language: "zh",
            <?php
            if(!empty($goods['goods_img'])){
                echo 'initialPreview:[\''.'<img src="'.$goods["goods_img"] .'" class="file-preview-image">\'],' ."\n";
                echo 'initialCaption:"' . $goods["goods_img"] . '",' . "\n";
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
        $("#other-btn a").click(function(){
            var num = $(this).parent().parent().children("div.col-sm-4").length;
            if(num > 2){
                swal({"title":"只能添加3个扩展分类","confirmButtonText":"确定"});
                return false;
            }
            var html = "<div class=\"col-sm-4\"><select class=\"form-control select2\" name=\"other_cat[]\" style=\"position: absolute;\">";
            html += $("#cat_id").html();
            html += "</select></div>"
            $(this).parent().parent().append(html);
            $(".select2").select2();
            $(".select2-container").css("width","100%");
            return false;
        });
        $("#local_img").click(function(){
            var local = "<div class=\"col-sm-8 form-group\"><label class=\"col-sm-2 control-label\">本地图片</label><div class=\"col-sm-10\"><input type=\"file\" class=\"form-control\" name=\"img_file[]\"><input class=\"form-control\" type=\"text\" name=\"img_file_desc[]\" placeholder=\"图片描述\"></div></div>";
            $("#upload-img-body").append(local);
        });
        $("#web_img").click(function(){
            var web = "<div class=\"col-sm-8 form-group\"><label class=\"col-sm-2 control-label\">网络图片</label><div class=\"col-sm-10\"><input type=\"text\" class=\"form-control\" name=\"img_url[]\" placeholder=\"网络图片url地址\"><input class=\"form-control\" type=\"text\" name=\"img_url_desc[]\" placeholder=\"图片描述\"></div></div>";
            $("#upload-img-body").append(web);
        });
        $("#search-goods").click(function(){
            $.ajax({
                type: "post",
                url:"/goods/search-goods",
                data:{"cat_id":$("#cat_id2").val(), "brand_id":$("#brand_id2").val(), "keyword":$("#keyword2").val()},
                dataType:"json",
                success: function (data) {
                    if(data.code == 1){
                        $("#source_goods1").children("option").remove();
                        $.each(data.data, function(n, value){
                            var options = "";
                            options += "<option value=\"" + value.goods_id + "\">" + value.goods_name + "</option>";
                            $("#source_goods1").append(options);
                        });
                    }else{
                        swal({"title":data.msg,"confirmButtonText":"确定"});
                    }
                },
                error: function () {
                    swal({"title":"请求失败，请刷新后重试！","confirmButtonText":"确定", "type":"error"});
                }

            });
            return false;
        });
        // 搜索配件相关产品
        $("#search-parts").click(function(){
            $.ajax({
                type: "post",
                url:"/goods/search-goods",
                data:{"cat_id":$("#cat_id3").val(), "brand_id":$("#brand_id3").val(), "keyword":$("#keyword3").val()},
                dataType:"json",
                success: function (data) {
                    if(data.code == 1){
                        $("#source_goods2").children("option").remove();
                        $.each(data.data, function(n, value){
                            var options = "";
                            options += "<option price=\"" + value.shop_price + "\" value=\"" + value.goods_id + "\">" + value.goods_name + "</option>";
                            $("#source_goods2").append(options);
                        });
                    }else{
                        swal({"title":data.msg,"confirmButtonText":"确定"});
                    }
                },
                error: function () {
                    swal({"title":"请求失败，请刷新后重试！","confirmButtonText":"确定", "type":"error"});
                }

            });
            return false;
        });

        // 文章检索
        $("#search-article").click(function(){
            $.ajax({
                type: "post",
                url:"/article/search-by-title",
                data:{"keywords":$("#article_title").val()},
                dataType:"json",
                success: function (data) {
                    if(data.code == 1){
                        $("#source_article").children("option").remove();
                        $.each(data.data, function(n, value){
                            var options = "";
                            options += "<option value=\"" + value.article_id + "\">" + value.title + "</option>";
                            $("#source_article").append(options);
                        });
                    }else{
                        swal({"title":data.msg,"confirmButtonText":"确定"});
                    }
                },
                error: function () {
                    swal({"title":"请求失败，请刷新后重试！","confirmButtonText":"确定", "type":"error"});
                }

            });
            return false;
        });
        if($("input[name=is_promote]").val() == 1){
            $("#is_promote1").removeAttr("disabled");
            $("#promote_date").removeAttr("disabled");
        }
        $("input[name=is_promote]").change(function(){
            if($("input[name=is_promote]").is(":checked")){
                $("input[name=is_promote]:checked").val(1);
                $("#is_promote1").removeAttr("disabled");
                $("#promote_date").removeAttr("disabled");
            }else{
                $("input[name=is_promote]").val(0);
                $("#is_promote1").attr("disabled", "disabled");
                $("#promote_date").attr("disabled", "disabled");
            };
        });
        $("input[name=is_goods_img_web]").change(function(){
            if($("input[name=is_goods_img_web]").is(":checked")){
                $("input[name=is_goods_img_web]:checked").val(1);
                $("#goods_img").attr("disabled", "disabled");
                $("#goods_img_url").removeAttr("disabled");
            }else{
                $("input[name=is_goods_img_web]").val(0);
                $("#goods_img_url").attr("disabled", "disabled");
                $("#goods_img").removeAttr("disabled");
            };
        });
        $("input[name=is_goods_thumb_web]").change(function(){
            if($("input[name=is_goods_thumb_web]").is(":checked")){
                $("input[name=is_goods_thumb_web]:checked").val(1);
                $("#goods_thumb").attr("disabled", "disabled");
                $("#goods_thumb_url").removeAttr("disabled");
            }else{
                $("input[name=is_goods_thumb_web]").val(0);
                $("#goods_thumb_url").attr("disabled", "disabled");
                $("#goods_thumb").removeAttr("disabled");
            };
        });
        var colorbox_params = {
            reposition:true,
            scalePhotos:true,
            scrolling:false,
            previous:"<i class=\"icon-arrow-left\"></i>",
            next:"<i class=\"icon-arrow-right\"></i>",
            close:"&times;",
            current:"{current} of {total}",
            maxWidth:"100%",
            maxHeight:"100%",
            onOpen:function(){
                document.body.style.overflow = "hidden";
            },
            onClosed:function(){
                document.body.style.overflow = "auto";
            },
            onComplete:function(){
                $.colorbox.resize();
            }
        };
        $(".gallery-list [data-rel=\"colorbox\"]").colorbox(colorbox_params);
        $("#cboxLoadingGraphic").append("<i class=\"icon-spinner orange\"></i>");

        var single = " -- 单向关联";
        var double = " -- 双向关联";
        // 添加选择
        $("#add").click(function(){
            var add = $("#source_goods1 option:selected");
            if(add.length < 1){
                swal({"title":"请先选择一个产品","confirmButtonText":"确定"});
                return false;
            }
            var is_double = $("input[name=is_double]:checked").val();
            if(is_double == 1){
                add.each(function(i){
                    $(this).text($(this).text() + double).val($(this).val() + "_" + is_double)
                });
            }else{
                add.each(function(i){
                    $(this).text($(this).text() + single).val($(this).val() + "_" + is_double);
                });
            }
            add.appendTo("#source_select1");
        });
        // 全部选择
        $("#addAll").click(function(){
            var all = $("#source_goods1 option");
            if(all.length < 1){
                swal({"title":"请先检索产品","confirmButtonText":"确定"});
                return false;
            }
            var is_double = $("input[name=is_single]:checked").val();
            if(is_double == 1){
                all.each(function(i){
                    $(this).text($(this).text() + double).val($(this).val() + "_" + is_double)
                });
            }else{
                all.each(function(i){
                    $(this).text($(this).text() + single).val($(this).val() + "_" + is_double);
                });
            }
            all.appendTo("#source_select1");
        });
        // 双击选择
        $("#source_goods1").dblclick(function(){
            var selected = $("#source_goods1 option:selected");
            var is_double = $("input[name=is_double]:checked").val();
            if(is_double == 1){
                selected.text( selected.text() + double).val(selected.val() + "_" + is_double).appendTo("#source_select1");
            }else{
                selected.text( selected.text() + single).val(selected.val() + "_" + is_double).appendTo("#source_select1");
            }
        });
        // 取消
        $("#cancel").click(function(){
            var cancel = $("#source_select1 option:selected");
            if(cancel.length < 1){
                swal({"title":"请先选择一个产品","confirmButtonText":"确定"});
                return false;
            }
            cancel.each(function(i){
                $(this).text(getName($(this).text(), 8)).val(getName($(this).val(), 2));
            });
            $("#source_select1 option:selected").appendTo("#source_goods1");
        });
        // 全部取消
        $("#cancelAll").click(function(){
            var all = $("#source_select1 option");
            if(all.length < 1){
                swal({"title":"还未选择产品","confirmButtonText":"确定"});
                return false;
            }
            all.each(function(i){
                $(this).text(getName($(this).text(), 8)).val(getName($(this).val(), 2));
            });
            all.appendTo("#source_goods1");
        });
        // 双击取消
        $("#source_select1").dblclick(function(){
            var selected = $("#source_select1 option:selected");
            selected.text(getName(selected.text(), 8)).val(getName(selected.val(), 2)).appendTo("#source_goods1");
        });
        $("#source_goods2").click(function(){
            var price = $("#source_goods2 option:selected").attr("price");
            $("#price2").val(price);
        });
        // 添加配件
        $("#add-price").click(function(){
            var add = $("#source_goods2 option:selected");
            if(add.length > 1){
                swal({"title":"一次只能选择一个产品","confirmButtonText":"确定"});
                return false;
            }
            var price = $("#price2").val();
            add.attr("new", price).text( add.text() + " -- [" + price + "]").val(add.val() + "_" + price).appendTo("#source_select2");
        });
        $("#source_goods2").dblclick(function(){
            var add = $("#source_goods2 option:selected");
            var price = $("#price2").val();
            add.attr("new", price).text( add.text() + " -- [" + price + "]").val(add.val() + "_" + price).appendTo("#source_select2");
        });
        // 删除配件
        $("#cancel-price").click(function(){
            var cancel = $("#source_select2 option:selected");
            var id = "_" + cancel.attr("new");
            var text = " -- [" + cancel.attr("new") + "]";
            cancel.removeAttr("new").text( getName(cancel.text(), text.length)).val(getName(cancel.val(), id.length)).appendTo("#source_goods2");
        });
        $("#source_select2").dblclick(function(){
            var cancel = $("#source_select2 option:selected");
            var id = "_" + cancel.attr("new");
            var text = " -- [" + cancel.attr("new") + "]";
            cancel.removeAttr("new").text( getName(cancel.text(), text.length)).val(getName(cancel.val(), id.length)).appendTo("#source_goods2");
        });
        // 全部删除
        $("#cancel-all").click(function(){
            var all = $("#source_select2 option");
            if(all.length < 1){
                swal({"title":"还未选择产品","confirmButtonText":"确定"});
                return false;
            }
            all.each(function(i){
                var id = "_" + $(this).attr("new");
                var text = " -- [" + $(this).attr("new") + "]";
                $(this).removeAttr("new").text( getName($(this).text(), text.length)).val(getName($(this).val(), id.length));
            });
            all.appendTo("#source_goods2");
        });
        // 添加文章
        $("#add-article").click(function(){
            var add = $("#source_article option:selected");
            if(add.length < 1){
                swal({"title":"请至少选择一篇文章","confirmButtonText":"确定"});
                return false;
            }
            add.appendTo("#select-article");
        });
        // 双击添加文章
        $("#source_article").dblclick(function(){
            $("#source_article option:selected").appendTo("#select-article");
        });
        // 全部添加
        $("#add-all-article").click(function(){
            $("#source_article option").appendTo("#select-article");
        });
        $("#cancel-article").click(function(){
            var cancel = $("#select-article option:selected");
            if(cancel.length < 1){
                swal({"title":"请至少选择一篇文章","confirmButtonText":"确定"});
                return false;
            }
            cancel.appendTo("#source_article");
        });
        // 双击添加文章
        $("#select-article").dblclick(function(){
            $("#select-article option:selected").appendTo("#source_article");
        });
        // 全部添加
        $("#cancel-all-article").click(function(){
            $("#select-article option").appendTo("#source_article");
        });
        $(".product-edit").click(function(){
            if($("#name").val() == ""){
                swal({"title":"商品名必须填写","confirmButtonText":"确定", "type":"warning"});
                return false;
            }
            if($("#cat_id").val() == 0){
                swal({"title":"商品分类必须填写","confirmButtonText":"确定", "type":"warning"});
                return false;
            }
            var article = [];
            var parts = [];
            var goods = [];
            var goods_options = $("#source_select1 option");
            for (var i = 0; i < goods_options.length; i++) {
                // 添加到数组里
                goods.push(goods_options.eq(i).val());
            };
            var parts_options = $("#source_select2 option");
            for (var i = 0; i < parts_options.length; i++) {
                // 添加到数组里
                parts.push(parts_options.eq(i).val());
            };
            var article_options = $("#select-article option");
            for (var i = 0; i < article_options.length; i++) {
                // 添加到数组里
                article.push(article_options.eq(i).val());
            };
            $("#edit-product").ajaxSubmit({
                type: "post",
                data: {"relation":goods, "parts": parts, "article": article},
                url:"/goods/update",
                success: function (data) {
                    if(data.code == 1){
                        swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                            window.location.href = "/goods/view?id="+data.data.goods_id;
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
        // 删除相册图片
        $(".del-gallery").click(function(){
            var $this = $(this);
            swal({
                "title":"您确定要删除吗？",
                "confirmButtonText":"确定",
                "closeOnConfirm":false
            },function(isConfirm){
                if(isConfirm){
                    $.ajax({
                        type: "post",
                        data: {"id":$this.attr("gid"), "iid": $this.attr("iid")},
                        url:"/goods/gallery",
                        success: function (data) {
                            if(data.code == 1){
                                swal({"title":data.msg,"confirmButtonText":"确定", "type":"success", "closeOnConfirm":false},function(){
                                    window.location.href = "/goods/view?id="+data.data.goods_id;
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
        $(".attr-add").click(function(){
            var $this = $(this).parent().parent().parent().parent();
            $this.find('.attr-group:last').after($this.find(".attr-group:first").clone());
            $this.find('.attr-group:last').find(".btn-info").addClass("btn-warning").removeClass("btn-info");
            $this.find('.attr-group:last').find(".attr-add").addClass("attr-delete").removeClass("attr-add");
            $this.find('.attr-group:last').find(".fa-plus").addClass("fa-remove").removeClass("fa-plus");
            $this.find('.attr-group:last').find(".form-control").removeClass("select2-hidden-accessible").removeAttr("tabindex").removeAttr("aria-hidden");
            $this.find('.attr-group:last').find(".filter_attr option").remove();
            $this.find('.attr-group:last').find(".select2-container").remove();
            $(".select2").select2();
            $(".select2-container").css("width","100%");
        });
        $('.add-volume').click(function () {
            var parent = $(".volume-group:last");
            var template = parent.clone();
            parent.after(template);
            $('.volume-group:last').css('margin', '10px 0').find('input').val(0);
            $('.volume-group:last').find(".btn-info").addClass("btn-warning").removeClass("btn-info");
            $('.volume-group:last').find(".add-volume").addClass("remove-volume").removeClass("add-volume");
            $('.volume-group:last').find(".fa-plus").addClass("fa-remove").removeClass("fa-plus");
        });
        $(document).on('click', '.remove-volume',function(){
            $(this).parent().parent().remove();
        });
        $("select[name=goods_type]").change(function(){
            $.ajax({
                url:'/goods/type',
                data:{"id":$(this).val(), "gid":$('input[name=goods_id]').val()},
                type: 'POST',
                dataType:'json',
                success: function (data) {
                    if(data.code == 1){
                        var textBody = $('#goods-type').find('.box-body');
                        textBody.html('');
                        textBody.html(data.data.html);
                        $('.select2').select2();
                        $(".select2-container").css("width","100%");

                    }else{
                        swal({"title":data.msg,"confirmButtonText":"确定", "type":"warning"});
                    }
                },
                error: function () {
                    swal({"title":"请求失败，请刷新后重试！","confirmButtonText":"确定", "type":"error"});
                }
            })
        });
        $(document).on("click", ".attr-delete", function(){
            $(this).parent().parent().parent().remove();
        });
        $(".select2-container").css("width","100%");
        function getName(name, len){
            var length = name.length-len;
            return name.substring(0, length);
        }
    </script>
<?php $this->endBlock(); ?>