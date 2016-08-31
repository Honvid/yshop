<?php
use yii\helpers\Html;
?>
<?php $this->beginPage();?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language;?>">
<head>
    <meta charset="<?=Yii::$app->charset;?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?=Html::csrfMetaTags();?>
    <title><?=Html::encode($this->title);?></title>
    <?php $this->head();?>
</head>
<body class="hold-transition skin-blue sidebar-mini" style="background: #eee;">
<?php $this->beginBody();?>
    <!-- 页面主体-->
    <?=$content;?>
    <footer class="content-footer">
        <div class="pull-right hidden-xs">
            Anything you want
        </div>
        <strong>Copyright &copy; 2015 <a href="#">Company</a>.</strong> All rights reserved.
    </footer>
<?php $this->endBody();?>

</body>
<?php if (isset($this->blocks['script'])): ?><?= $this->blocks['script'] ?>
<?php endif; ?>
<?php $this->endPage();?>
</html>
