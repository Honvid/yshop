<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;

AppAsset::register($this);
$this->title = $exception->statusCode.'-'.$exception->getMessage();
?>
<section class="content">
    <?php if(YII_DEBUG) { ?>
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-warning"></i> <?php echo $exception->statusCode.':'.$exception->getMessage(); ?></h4>
        <?php echo $exception; ?>
    </div>
    <?php } else {?>
        <div class="error-page">
            <h2 class="headline text-yellow"><?php echo $exception->statusCode; ?></h2>
            <div class="error-content" style="padding-top: 32px;">
                <h3><i class="fa fa-warning text-yellow"></i> <?php echo $exception->getMessage(); ?></h3>
                <p>
                    我们马上修复该问题,同时,您可以点击 <a href="/site/index">返回首页</a>
                </p>
            </div>
        </div>
    <?php }?>
</section>