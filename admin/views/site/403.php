<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;

AppAsset::register($this);
$this->title = '访问受限';
?>
<section class="content">
    <?php if(!YII_DEBUG) { ?>
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> <?php echo $exception->statusCode.':您没有访问此方法的权限'; ?></h4>
            <?php echo $exception; ?>
        </div>
    <?php } else {?>
        <div class="error-page">
            <h2 class="headline text-yellow"><?php echo $exception->statusCode; ?></h2>
            <div class="error-content" style="padding-top: 32px;">
                <h3><i class="fa fa-warning text-yellow"></i> 您没有访问此方法的权限</h3>
                <p>
                    您可以联系管理员申请权限,<?php echo !empty($url) ? ' 或者 <a href="'.$url.'">返回上一页</a>' : ''?> 或者 <a href="/site/index">返回首页</a>
                </p>
            </div>
        </div>
    <?php }?>
</section>