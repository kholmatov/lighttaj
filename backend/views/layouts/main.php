<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
//use yii\bootstrap\Icon;
//use yii\bootstrap\NavBar;
//use yii\widgets\Breadcrumbs;
//use yii\helpers\Url;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>

    <meta charset="<?= Yii::$app->charset ?>">
    <?=Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php if(!Yii::$app->user->isGuest):?>
        <?php $this->head() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">

        <link href="<?=Yii::$app->request->baseUrl?>/them/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?=Yii::$app->request->baseUrl?>/them/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
        <link href="<?=Yii::$app->request->baseUrl?>/them/css/font-awesome.css" rel="stylesheet">
        <link href="<?=Yii::$app->request->baseUrl?>/them/css/style.css" rel="stylesheet">
        <link href="<?=Yii::$app->request->baseUrl?>/them/css/pages/dashboard.css" rel="stylesheet">
        <script src="<?=Yii::$app->request->baseUrl?>/them/js/jquery-1.7.2.min.js"></script>
        <script src="<?=Yii::$app->request->baseUrl?>/them/js/bootstrap.js"></script>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    <?php else:?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link href="<?=Yii::$app->request->baseUrl?>/them/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->request->baseUrl?>/them/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=Yii::$app->request->baseUrl?>/them/css/font-awesome.css" rel="stylesheet">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
        <link href="<?=Yii::$app->request->baseUrl?>/them/css/style.css" rel="stylesheet" type="text/css">
        <link href="<?=Yii::$app->request->baseUrl?>/them/css/pages/signin.css" rel="stylesheet" type="text/css">
    <?php endif;?>
</head>
<body>
<?php $this->beginBody() ?>
<?php if(!Yii::$app->user->isGuest):?>
    <!--Start My Content-->

    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                        class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="/">lighttaj</a>
                <div class="nav-collapse">
                    <ul class="nav pull-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-cog"></i> Account <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="javascript:;">Settings</a></li>
                                <li><a href="javascript:;">Help</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                    class="icon-user"></i> <?=Yii::$app->user->identity->username;?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="javascript:;">Profile</a></li>
                                <li>
                                   <?=Html::a('Logout',['/site/logout'],['data-method'=>'post']);?>
                                </li>
                            </ul>
                        </li>
                    </ul>
<!--                    <form class="navbar-search pull-right">-->
<!--                        <input type="text" class="search-query" placeholder="Search">-->
<!--                    </form>-->
                </div>
                <!--/.nav-collapse -->
            </div>
            <!-- /container -->
        </div>
        <!-- /navbar-inner -->
    </div>
    <!-- /navbar -->


    <div class="subnavbar">
        <div class="subnavbar-inner">
            <div class="container">
                <?php

                echo Nav::widget([
                    'items' => [//Flagged Category
                        //['label' => '<i class="icon-dashboard"></i><span>'.Yii::t('lighttaj','Dashboard').'</span>','url' => ['/site/index']],
                        ['label' => '<i class="icon-bar-chart"></i><span>'.Yii::t('lighttaj','Stats').'</span>','url' => ['/site/index']],
                        ['label' => '<i class="icon-tags"></i><span>'.Yii::t('lighttaj','Deals').'</span>','url' => ['/deal/index']],
                        ['label' => '<i class="icon-user"></i><span>'.Yii::t('lighttaj','Users').'</span>','url' => ['/user/index']],
                        ['label' => '<i class="icon-flag"></i><span>'.Yii::t('lighttaj','Flagged').'</span>','url' => ['/deal/flagged']],
                        ['label' => '<i class="icon-bookmark-empty"></i><span>'.Yii::t('lighttaj','Suspended').'</span>','url' => ['/deal/suspended']],
                        ['label' => '<i class="icon-folder-close"></i><span>'.Yii::t('lighttaj','Category').'</span>','url' => ['/category/index']],
                    ],
                    'options' => ['class' => 'mainnav'],
                    'encodeLabels' => false
                ]);

                ?>
            </div>
            <!-- /container -->
        </div>
        <!-- /subnavbar-inner -->
    </div>
    <!-- /subnavbar -->


    <div class="main">
        <div class="main-inner">
            <div class="container">
                <?= Alert::widget() ?>
                <?= $content ?>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /main-inner -->
    </div>
    <!-- /main -->

    <div class="footer">
        <div class="footer-inner">
            <div class="container">
                <div class="row">
                    <div class="span12"> &copy; <?= date('Y') ?> <a href="/">lighttaj</a>. </div>
                    <!-- /span12 -->
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /footer-inner -->
    </div>
    <!-- /footer -->

<?php else:?>
    <?= $content ?>
<?php endif;?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
