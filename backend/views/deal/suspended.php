<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\AssetManager;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('lighttaj', 'Suspended Deals');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-index">
    <div class="widget widget-nopad mywiget">

        <!-- /widget-header -->
        <?php $title = Html::encode($this->title);

              $mySearch='<div style="float:right;margin:3px 5px 0 0">
                        <form action="" method="GET" class="filters">
                            <input type="text" name="DealSearch[searchstring]" placeholder="Search" value="'.$searchModel->searchstring.'" class="form-control">
                        </form>
                    </div>';


        ?>



        <?php
          //    $myAction  = Html::beginForm(['controller/bulk'],'post');
            //  $myAction .= Html::dropDownList('action','',[''=>'With selected: ',
              //    's'=>'Suspend',
                //  'r'=>'Remove'/*,'nc'=>'No Confirmed'*/],['class'=>'dropdown',]);
              //$myAction .= Html::submitButton('Send', ['class' => 'btn btn-info',]);
        //<div class='myaction' style='float:left'>".$myAction."</div>


        ?>

        <div class="widget-content">
            <div class="widget big-stats-container">
                <div class="widget-content">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
        'layout'=>"{pager}\n<div class=\"widget-header\"><i class=\"icon-bookmark-empty\"></i>
            <h3>".$title."</h3>".$mySearch."{summary}</div>\n{items}\n<div style='clear:both'></div>{pager}",
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //['class' => 'yii\grid\CheckboxColumn','headerOptions' => ['width' => '5']],
            [
                'attribute'=>'id',
                'label'=>'ID',
                'headerOptions' => ['width' => '50']
            ],
            [
                'attribute' => 'Thumbnail',
                'format' => 'raw',
                'headerOptions' => ['width' => '100'],
                'value' => function($data) {
                    $myImage = AssetManager::fetchImageFilesForDeal($data->id,$data->imageList);
                    if(count($myImage) > 0) {

                        return Html::a(
                            Html::tag('div',
                                Html::img ($myImage[0], [
                                    'alt' => $data->title,
                                    'style' => 'width:100px;'
                                ])
                            ),
                                ['view','id' => $data->id],
                                ['class' => 'myimglist']
                            );
                    }

                    return Html::a(
                        Html::tag('div',
                            Html::img ('../nophoto.jpg', [
                            'alt' => $data->title,
                            'style' => 'width:100px;'
                             ])
                        ),
                        ['view','id' => $data->id],
                        ['class' => 'myimglist']

                    );

                }
            ],
            [
                'label' => 'Title',
                'attribute'=>'title',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->title,
                        Url::toRoute(['/deal/view','id'=>$data->id]),
                        [
                            'title' => 'view',
                            //'target' => '_blank'
                        ]
                    );
                },
               // 'headerOptions' => ['width'=>'80']
            ],
            [
                'attribute' => 'category',
                'value' => 'category.name',
                'headerOptions' => ['width' => '100'],
            ],
            [
                'attribute' => 'storeName',
                'format' => 'raw',
                'value'=>'storeName',
//
//                'value' => function($data){
//                    return iconv('ISO-8859-1', 'utf-8', $data->storeName);
//                 },
                'headerOptions' => ['width' => '50']
            ],
            [
                'attribute' => 'user',
                'label' => 'User',
                'value' => 'user.username',
                'headerOptions' => ['width' => '100'],
            ],
            [
                'attribute'=>'dateCreated',
                'label'=>'Date posted',
                'format'=>'datetime',
                'headerOptions' => ['width' => '10'],
            ],
            //'status',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data){
                          return '<center><div style="color:#ab2610"><span class="glyphicon icon-bookmark-empty" style="color:#ab2610" alt="Suspended" title="Suspended"></span> Suspended</div></center>';
                },
                'headerOptions' => ['width' => '60']
            ],
            [
                //'label' => 'Action',
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '15'],
                'template'    => '{view}',

            ],

        ],
    ]); ?>
                </div>
                <!-- /widget-content -->

            </div>
        </div>
    </div>


</div>
