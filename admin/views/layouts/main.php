<?php
    use admin\assets\AppAsset;
    use yii\helpers\Html;
    AppAsset::register($this);
?>
<?php $this->beginPage();?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language;?>" ng-app="mall">
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
    <body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody();?>
        <div class="wrapper">
            <header class="main-header">
                <a href="index2.html" class="logo">
                    <span class="logo-mini"><b>A</b>LT</span>
                    <span class="logo-lg"><b>Admin</b>LTE</span>
                </a>

                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Messages: style can be found in dropdown.less-->
                            <li class="dropdown messages-menu">
                                <!-- Menu toggle button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="label label-success">4</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 4 messages</li>
                                    <li>
                                        <!-- inner menu: contains the messages -->
                                        <ul class="menu">
                                            <li><!-- start message -->
                                                <a href="#">
                                                    <div class="pull-left">
                                                        <!-- User Image -->
                                                        <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                                    </div>
                                                    <!-- Message title and timestamp -->
                                                    <h4>
                                                        Support Team
                                                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                    </h4>
                                                    <!-- The message -->
                                                    <p>Why not buy a new awesome theme?</p>
                                                </a>
                                            </li><!-- end message -->
                                        </ul><!-- /.menu -->
                                    </li>
                                    <li class="footer"><a href="#">See All Messages</a></li>
                                </ul>
                            </li><!-- /.messages-menu -->

                            <!-- Notifications Menu -->
                            <li class="dropdown notifications-menu">
                                <!-- Menu toggle button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell-o"></i>
                                    <span class="label label-warning">10</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 10 notifications</li>
                                    <li>
                                        <!-- Inner Menu: contains the notifications -->
                                        <ul class="menu">
                                            <li><!-- start notification -->
                                                <a href="#">
                                                    <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                                </a>
                                            </li><!-- end notification -->
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="#">View all</a></li>
                                </ul>
                            </li>
                            <!-- Tasks Menu -->
                            <li class="dropdown tasks-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-flag-o"></i>
                                    <span class="label label-danger">9</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 9 tasks</li>
                                    <li>
                                        <!-- Inner menu: contains the tasks -->
                                        <ul class="menu">
                                            <li><!-- Task item -->
                                                <a href="#">
                                                    <!-- Task title and progress text -->
                                                    <h3>
                                                        Design some buttons
                                                        <small class="pull-right">20%</small>
                                                    </h3>
                                                    <!-- The progress bar -->
                                                    <div class="progress xs">
                                                        <!-- Change the css width attribute to simulate progress -->
                                                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="sr-only">20% Complete</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li><!-- end task item -->
                                        </ul>
                                    </li>
                                    <li class="footer">
                                        <a href="#">View all tasks</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- The user image in the navbar-->
                                    <img src="/img/user2-160x160.jpg" class="user-image" alt="User Image">
                                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                    <span class="hidden-xs"><?php echo Yii::$app->session->get('admin_name'); ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header">
                                        <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                        <p>
                                            <?php echo Yii::$app->session->get('admin_name'); ?> - Web Developer
                                            <small>Member since Nov. 2012</small>
                                        </p>
                                    </li>
                                    <!-- Menu Body -->
                                    <li class="user-body">
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Followers</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Friends</a>
                                        </div>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="#" class="btn btn-default btn-flat">注销登陆</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- 左侧内容 -->
            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">

                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo Yii::$app->session->get('admin_name'); ?></p>
                            <!-- Status -->
                            <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
                        </div>
                    </div>
                    <!-- 搜索 -->
                    <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="请输入...">
                      <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                      </span>
                        </div>
                    </form>
                    <!-- 导航 -->
                    <ul class="sidebar-menu">
                        <li class="header">控制台</li>
                        <?php if(!empty($this->context->_menu)) foreach($this->context->_menu as $menu){ ?>

                            <?php if(empty($menu['child'])){ ?>
                        <li<?php echo $menu['active'] == 1 ? ' class="active"' : '';?>><a href="<?php echo $menu['url']?>"><i class="<?php echo $menu['icon']?>"></i> <span><?php echo $menu['name']?></span></a></li>
                            <?php }else { ?>
                        <li class="treeview<?php echo $menu['active'] == 1 ? ' active' : '';?>">
                            <a href="<?php echo $menu['url']?>"><i class="<?php echo $menu['icon']?>"></i> <span><?php echo $menu['name']?></span> <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <?php foreach($menu['child'] as $child){ ?>
                                <li<?php echo $child['active'] == 1 ? ' class="active"' : '';?>><a href="<?php echo $child['url']?>"><span class="<?php echo $child['icon']?>"></span><?php echo $child['name']?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                            <?php } }?>
<!--                        <li class="treeview active">-->
<!--                            <a href="#"><i class="fa fa-laptop"></i> <span>系统设置</span> <i class="fa fa-angle-left pull-right"></i></a>-->
<!--                            <ul class="treeview-menu">-->
<!--                                <li><a href="/menu/index"><span class="fa fa-user"></span>菜单管理</a></li>-->
<!--                                <li><a href="/role/index"><span class="fa fa-users"></span>角色管理</a></li>-->
<!--                                <li><a href="/manager/index"><span class="fa fa-user"></span>管理员列表</a></li>-->
<!--                            </ul>-->
<!--                        </li>-->
                    </ul><!-- /导航 -->
                </section>
                <!-- /侧边栏 -->
            </aside>

            <!-- 页面主体-->
            <div class="content-wrapper">
                <?=$content;?>
            </div>
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    Anything you want
                </div>
                <strong>Copyright &copy; 2015 <a href="#">Company</a>.</strong> All rights reserved.
            </footer>
            <aside class="control-sidebar control-sidebar-dark">
                <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                    <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="control-sidebar-home-tab">
                        <h3 class="control-sidebar-heading">Recent Activity</h3>
                        <ul class="control-sidebar-menu">
                            <li>
                                <a href="javascript::;">
                                    <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                                    <div class="menu-info">
                                        <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                                        <p>Will be 23 on April 24th</p>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <h3 class="control-sidebar-heading">Tasks Progress</h3>
                        <ul class="control-sidebar-menu">
                            <li>
                                <a href="javascript::;">
                                    <h4 class="control-sidebar-subheading">
                                        Custom Template Design
                                        <span class="label label-danger pull-right">70%</span>
                                    </h4>
                                    <div class="progress progress-xxs">
                                        <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                    <div class="tab-pane" id="control-sidebar-settings-tab">
                        <form method="post">
                            <h3 class="control-sidebar-heading">General Settings</h3>
                            <div class="form-group">
                                <label class="control-sidebar-subheading">
                                    Report panel usage
                                    <input type="checkbox" class="pull-right" checked>
                                </label>
                                <p>
                                    Some information about this general settings option
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>
            <div class="control-sidebar-bg"></div>
        </div>
        <?php $this->endBody();?>

    </body>
<?php if (isset($this->blocks['script'])): ?><?= $this->blocks['script'] ?>
    <?php endif; ?>
<?php $this->endPage();?>
</html>
