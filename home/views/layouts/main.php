<?php
    use home\assets\AppAsset;
    use yii\helpers\Html;

    AppAsset::register($this);
?>
<?php $this->beginPage();?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language;?>">
<head>
    <meta charset="<?=Yii::$app->charset;?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=Html::csrfMetaTags();?>
    <title><?=Html::encode($this->title);?></title>
    <?php $this->head();?>
</head>
<body>
    <?php $this->beginBody();?>
    <div class="wrap clearfix">
        <div class="container">
            <?=$content;?>
        </div>
    </div>
    <footer class="footer">
        <div class="container"></div>
    </footer>
    <?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>
