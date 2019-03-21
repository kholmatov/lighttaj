<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\assets\AssetManager;
use backend\models\Category;
use backend\models\User;
use backend\models\UserDealLike;
use backend\models\UserDealFavorite;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Deal */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('lighttaj', 'Deals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$myImage = AssetManager::fetchImageFilesForDeal($model->id,$model->imageList);
$img1='../nophoto.jpg';
if(count($myImage) > 0){
    $img1 = $myImage[0];
}
?>

<div class="row">
    <div class="span12">
        <div class="span6 form-action"  style="padding:0 0 20px 0">
            <?php
            //0 - active
            //1 - flagged
            //2 - suspend
            //3 - expired
            if ($model->status==0):
                $backurl = Url::toRoute('index');
                $sts='<div style="color:#00ab30"><span title="Active" alt="Active" style="color:#00ab30" class="glyphicon glyphicon-ok"></span> Active</div>';
                echo Html::a(Yii::t('lighttaj', 'Suspend Deal'), ['dosuspend', 'id' => $model->id],
                    ['class' => 'btn btn-danger','data-confirm' => Yii::t('yii', 'Are you sure you want to suspend this deal?')]);
            elseif($model->status==1):
                $backurl = Url::toRoute('flagged');
                echo Html::a(Yii::t('lighttaj', 'Suspend Deal'), ['dosuspend', 'id' => $model->id],
                    ['class' => 'btn btn-danger','data-confirm' => Yii::t('yii', 'Are you sure you want to suspend this deal?')]);
                echo Html::a(Yii::t('lighttaj', 'Remove Flag'), ['doactive', 'id' => $model->id],
                    ['class' => 'btn btn-success','data-confirm' => Yii::t('yii', 'Are you sure you want to remove flag?')]);
                $sts='<div style="color:#ab2610"><span title="Flagged" alt="Flagged" style="color:#ab2610" class="glyphicon glyphicon-flag"></span> Flagged</div>';
            elseif($model->status==2):
                $backurl = Url::toRoute('suspended');

                echo Html::a(Yii::t('lighttaj', 'Activate'), ['doactive', 'id' => $model->id],
                    ['class' => 'btn btn-success','data-confirm' => Yii::t('yii', 'Are you sure you want to activate this deal?')]);

                $sts='<div style="color:#ab2610"><span class="glyphicon icon-bookmark-empty" style="color:#ab2610" alt="Suspend title="Suspend"></span> Suspended</div>';
            elseif($model->status==3):
                $backurl = Url::toRoute('index');
                $sts='<div style="color:#ab2610"><span class="glyphicon glyphicon-remove" style="color:#ab2610" alt="Expired" title="Expired"></span> Expired</div>';
            endif;
            //
            ?>

            <?php
            $session = Yii::$app->session;
            $session->open();
            $myUrl="";
            if ($session->has('back')) $myUrl = $session->get('back');
            echo Html::a(Yii::t('lighttaj','Back'), $backurl.$myUrl,['class' => 'btn btn-invert']);
            ?>
        </div>
    </div>
    <div class="span12 mydeal">

        <div class="span4" style="width:370px !important">
            <div class="widget widget-nopad">
                <div class="widget-content">
                    <div class="widget big-stats-container mytouch">
                        <div class="widget-content">
                                    <div class="img-bigstats" >
                                        <a href="<?= $img1; ?>">
                                            <img src="<?= $img1; ?>" width="100%">
                                        </a>
                                    </div>
                                    <?php
                              if(count($myImage)>1):
                                    echo'<div id="big_stats" class="cf">';
                                       for($i=1;$i < count($myImage);$i++) {
                                           echo' <a href = "'.$myImage[$i].'" >
                                           <div class="mystat" style="background: url('.$myImage[$i].') 100% 100% no-repeat; background-size: cover;" >
                                           </div>
                                            </a>';
                                        }
                                    echo'</div>';
                                endif;
                            // <img src = "'.$myImage[$i].'" width = "100%" >
                            ?>

                        </div>
                        <!-- /widget-content -->

                    </div>
                </div>
            </div>
            <!-- /widget -->
        </div>


        <div class="span7">

                    <div class="widget widget-table action-table">
                        <div class="widget-content">
                            <?php
                                //print_r($model);
                                $category = Category::findOne($model->categoryID);
                                $user = User::findOne($model->userID);
                                $countLike =  UserDealLike::find()->where(['dealID'=>$model->id,'likeVal'=>1])->count();
                                $countDislike =  UserDealLike::find()->where(['dealID'=>$model->id,'likeVal'=>'-1'])->count();
                                $countFavorite =  UserDealFavorite::find()->where(['dealID'=>$model->id])->count();

                            ?>
                            <?= DetailView::widget([
                                'model' => $model,
                                'template'=>'<tr><td width="85px"><b>{label}</b></td><td>{value}</td></tr>',
                                'options' => ['class' => 'table table-striped table-bordered'],
                                'attributes' => [
                                    [
                                        'attribute'=>'id',
                                        'label'=>'Deal ID',
                                    ],
                                    'title',
                                    'description',
                                    [
                                        'attribute' => 'category',
                                        'label'=>'Category',
                                        'value' => $category->name
                                    ],
                                    'storeName',
                                    'storeAddress',
                                    [
                                        'attribute' => 'user',
                                        'label' => 'User',
                                        'value' => $user->username
                                    ],
                                    'dateCreated',
                                    'dateEnding',
                                    //'lat',
                                    //'lon',

                                    //'priceType',
                                    'priceSale',
                                    'priceRegular',
                                    'offDollar',
                                    'offPercent',
                                    'units',
                                    'benefit',
                                    //'status',
                                    [
                                        'attribute'=>'favorite',
                                        'label'=>'Total Favorites',
                                        'format'=>'raw',
                                        'value'=> $countFavorite
                                    ],
                                    [
                                        'attribute'=>'like',
                                        'label'=>'Total Likes',
                                        'format'=>'raw',
                                        'value'=>$countLike
                                    ],
                                    [
                                        'attribute'=>'dislike',
                                        'label'=>'Total Dislikes',
                                        'format'=>'raw',
                                        'value'=>$countDislike
                                    ],


                                    [
                                        'attribute'=>'status',
                                        'label'=>'Status',
                                        'format'=>'raw',
                                        'value'=>$sts
                                    ]


                                ],
                            ]) ?>
                        </div>
                    </div>

        </div>


    </div>
</div>
<?php
$myjs="$(document).ready( function() { $('.mytouch a').touchTouch(); }); ";
$this->registerJsFile(Yii::$app->request->baseUrl.'/touchTouch/touchTouch.jquery.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs($myjs,$this::POS_END);
$this->registerCssFile(Yii::$app->request->baseUrl.'/touchTouch/touchTouch.css');
?>
