<?php
/**
 * @author Honvid
 */
use admin\assets\AppAsset;

AppAsset::register($this);
$this->title = "首页";
?>
<section class="content-header">
    <h1>
        Page Header
        <small>Optional description</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#fa-icons" data-toggle="tab">商品列表</a></li>
                    <li><a href="#glyphicons" data-toggle="tab">备用</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="fa-icons">
                        备用
                    </div>

                    <div class="tab-pane" id="glyphicons">
                    </div>
                </div><!-- /.tab-content -->
            </div>
        </div>
    </div>
</section><!-- /.content -->