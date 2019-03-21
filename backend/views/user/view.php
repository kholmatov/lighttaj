<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\assets\AssetManager;
use backend\models\Deal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('lighttaj', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="span12">
        <div class="span4" style="padding:0 0 20px 0">
            <?php

            if($model->confirmedEmail==1) $confirmed = 'Yes';
            else $confirmed = 'No';

            if($model->status==0) {
                $sts = '<div style="color:#00ab30"><span title="Active" alt="Active" style="color:#00ab30" class="glyphicon glyphicon-ok"></span> Active</div>';
                echo Html::a(Yii::t('lighttaj', 'Suspend'), ['dosuspend', 'id' => $model->id],
                    ['class' => 'btn btn-danger','data-confirm' => Yii::t('yii', 'Are you sure you want to suspended this User?')]);
            }else {
                $sts = '<div style="color:#ab2610"><span title="Suspended" alt="Suspended" style="color:#ab2610" class="glyphicon glyphicon-remove"></span> Suspended</div>';
                echo Html::a(Yii::t('lighttaj', 'Activate'), ['doactive', 'id' => $model->id],['class' => 'btn btn-success']);
            }

            /*
            echo Html::a(Yii::t('lighttaj', 'Delete'), ['delete', 'id' => $model->id],
                ['class' => 'btn btn-warning','data-confirm' => Yii::t('yii', 'Are you sure you want to delete this User?'),'data-method'=>'POST']);
            */

            ?>
            <?php
            $session = Yii::$app->session;
            $session->open();
            $myUrl="";
            if ($session->has('user_back')) $myUrl = $session->get('user_back');
            echo Html::a(Yii::t('lighttaj','Back'), Url::toRoute('index').$myUrl,['class' => 'btn btn-invert']);
            ?>
        </div>
    </div>


    <div class="span12">

        <div class="span2">
            <div class="widget widget-nopad">
                <div class="widget-content">
                    <div class="widget big-stats-container mytouch myusers">
                        <div class="widget-content">
                            <div class="img-bigstats" >
                                <?php
                                if($model->hasPhoto==1) {
                                    $img = AssetManager::URLForUserImageFile($model->id);
                                }else{
                                    $img = '../nophotouser.jpg';
                                }
                                ?>
                                <a href="<?=$img;?>">
                                    <img src="<?=$img;?>" width="100%">
                                </a>
                            </div>

                        </div>
                        <!-- /widget-content -->

                    </div>
                </div>
            </div>
            <!-- /widget -->
        </div>


        <div class="span7">
            <?php



            //get Deals
            $count = Deal::find()->where(array('userID' => $model->id))->count();
            $active = Deal::find()->where(array('userID' => $model->id,'status'=>0))->count();
            $flagged = Deal::find()->where(array('userID' => $model->id,'status'=>1))->count();
            $suspended = Deal::find()->where(array('userID' => $model->id,'status'=>2))->count();
            $expired = Deal::find()->where(array('userID' => $model->id,'status'=>3))->count();


            ?>
            <div class="widget widget-table action-table">
                <div class="widget-content">
                    <?= DetailView::widget([
                        'model' => $model,
                        'template'=>'<tr><td width="120px"><b>{label}</b></td><td>{value}</td></tr>',
                        'options' => ['class' => 'table table-striped table-bordered'],
                        'attributes' => [
                            'id',
                            'username',
                            'email:email',
                            //'password',
                            //'confirmedEmail:email',
                            [
                                'attribute' => 'confirmedEmail',
                                'format' => 'raw',
                                'value' =>$confirmed

                            ],
                            [
                                'attribute' => 'deals',
                                'format' => 'raw',
                                'value' =>$count

                            ],
                            [
                                'attribute' => 'Deals active',
                                'format' => 'raw',
                                'value' =>$active

                            ],
                            [
                                'attribute' => 'Deals flagged',
                                'format' => 'raw',
                                'value' =>$flagged

                            ],
                            [
                                'attribute' => 'Deals suspended',
                                'format' => 'raw',
                                'value' =>$suspended

                            ],
                            [
                                'attribute' => 'Deals expired',
                                'format' => 'raw',
                                'value' =>$expired

                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' =>$sts

                            ],
//                            [
//                                'attribute' => 'user_profile',
//                                'value' => getUserProfiles()->description,
//
//                            ],
                        ],
                    ]) ?>
                </div>
            </div>

        </div>


    </div>

</div>
<style>
    .widget{
        margin-bottom:0px;
    }
    .btn-warning{
        margin-left:3px;
    }
</style>
<?php
$myjs="$(document).ready( function() { $('.mytouch a').touchTouch(); }); ";
$this->registerJsFile(Yii::$app->request->baseUrl.'/touchTouch/touchTouch.jquery.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs($myjs,$this::POS_END);
$this->registerCssFile(Yii::$app->request->baseUrl.'/touchTouch/touchTouch.css');
?>