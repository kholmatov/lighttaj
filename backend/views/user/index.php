<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\AssetManager;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('lighttaj', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>-->
        <?php //echo Html::a(Yii::t('lighttaj', 'Create User'), ['create'], ['class' => 'btn btn-success'])

         ?>
<!--    </p>-->

    <div class="widget widget-nopad mywiget">

        <!-- /widget-header -->
        <?php
            $title=Html::encode($this->title);
            $mySearch='<div style="float:right;margin:3px 5px 0 0">
                        <form action="" method="GET" class="filters">
                            <input type="text" name="UserSearch[searchstring]" placeholder="Search" value="'.$searchModel->searchstring.'" class="form-control">
                        </form>
                    </div>';
        ?>
        <div class="widget-content">
            <div class="widget big-stats-container">
                <div class="widget-content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'layout'=>"{pager}\n<div class=\"widget-header\"><i class=\"icon-user\"></i>
                        <h3>".$title."</h3>".$mySearch."{summary}</div>\n{items}\n<div style='clear:both'></div>{pager}",
                        'columns' => [
                            //['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute'=>'id',
                                'label'=>'ID',
                                'headerOptions' => ['width' => '30'],
                            ],
                            [
                                'attribute' => 'Photo',
                                'format'=> 'raw',
                                'headerOptions'=>['width'=>'50'],
                                'value'=>function($data){



                                    if($data->hasPhoto==1){
                                        $usrImage = AssetManager::URLForUserImageFile($data->id);
                                        return Html::a(
                                            Html::tag('div',
                                                Html::img ($usrImage, [
                                                    'alt' => $data->username,
                                                    'style' => 'width:50px;'
                                                ])
                                            ),
                                            ['view','id' => $data->id],
                                            ['class' => 'myimglist']
                                        );
                                    }

                                    return Html::a(
                                        Html::tag('div',
                                            Html::img ('../nophotouser.jpg', [
                                                'alt' => $data->username,
                                                'style' => 'width:50px;'
                                            ])
                                        ),
                                        ['view','id' => $data->id],
                                        ['class' => 'myimglist']

                                    );

                                }
                            ],
                            [
                                'label' => 'Username',
                                'attribute'=>'username',
                                'format' => 'raw',
                                'value' => function($data){
                                    return Html::a(
                                        $data->username,
                                        Url::toRoute(['/user/view','id'=>$data->id]),
                                        [
                                            'title' => 'view',
                                            //'target' => '_blank'
                                        ]
                                    );
                                },
                                // 'headerOptions' => ['width'=>'80']
                            ],
                            'email:email',
                            //'password',
                            //'confirmedEmail:email',
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function($data){
                                    if($data->status==0)
                                        return '<center style="color:#00ab30"><span title="Active" alt="Active" style="color:#00ab30" class="glyphicon glyphicon-ok"></span> Active</center>';
                                    else
                                        return '<center style="color:#ab2610"><span title="Suspend" alt="Suspend" style="color:#ab2610" class="glyphicon glyphicon-remove"></span> Suspend</center>';
                                },
                                'headerOptions' => ['width' => '60']
                            ],
                            ['class' => 'yii\grid\ActionColumn','template'    => '{view}', 'headerOptions' => ['width' => '15']],
                        ],
                    ]); ?>
                </div>
                <!-- /widget-content -->

            </div>
        </div>
    </div>

</div>