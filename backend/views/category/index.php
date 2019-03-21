<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\AssetManager;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('lighttaj', 'Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <div class="row">
        <div class="span3" style="padding:0 0 20px 0">
            <?=Html::a(Yii::t('app', 'Create Category'), ['create'], ['class' => 'btn btn-success']);?>
        </div>
    </div>
    <div class="widget widget-nopad mywiget">

        <!-- /widget-header -->
        <?php

        $title=Html::encode($this->title);
        $mySearch='<div style="float:right;margin:3px 5px 0 0">
                        <form action="" method="GET" class="filters">
                            <input type="text" name="CategorySearch[searchstring]" placeholder="Search" value="'.$searchModel->searchstring.'" class="form-control">
                        </form>
                    </div>';
        ?>

        <div class="widget-content">
            <div class="widget big-stats-container">
                <div class="widget-content">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'layout'=>"{pager}\n<div class=\"widget-header\"><i class=\"icon-folder-close\"></i>
            <h3>".$title."</h3>".$mySearch."{summary}</div>\n{items}",
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
                    $categoryImage = AssetManager::URLForCategoryIconDirectory().'/'.$data->id.'_med.png?rnd='.rand(0,100);

                    if($categoryImage!=""){
                        return Html::a(
                            Html::tag('div',
                                Html::img ($categoryImage, [
                                    'alt' => $data->name,
                                    'style' => 'width:50px;'
                                ])
                            ),
                            ['update','id' => $data->id],
                            ['class' => 'myimglist']
                        );
                    }

                    return Html::a(
                        Html::tag('div',
                            Html::img ('../nophoto.jpg', [
                                'alt' => $data->name,
                                'style' => 'width:50px;'
                            ])
                        ),
                        ['update','id' => $data->id],
                        ['class' => 'myimglist']

                    );

                }
            ],
            [
                'label' => 'Name',
                'attribute'=>'name',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->name,
                        Url::toRoute(['/category/update','id'=>$data->id]),
                        [
                            'title' => 'view',
                            //'target' => '_blank'
                        ]
                    );
                },
                // 'headerOptions' => ['width'=>'80']
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data){
                    if($data->status)
                        return '<center style="color:#00ab30"><span title="Active" alt="Active" style="color:#00ab30" class="glyphicon glyphicon-ok"></span> Activate</center>';
                    else
                        return '<center style="color:#ab2610"><span title="Deactive" alt="Deactive" style="color:#ab2610" class="glyphicon glyphicon-remove"></span> Deactivate</center>';
                },
                'headerOptions' => ['width' => '60']
            ],
            ['class' => 'yii\grid\ActionColumn','template'    => '{update}{delete}','headerOptions' => ['width' => '30']],
        ],
    ]); ?>
                </div>
                <!-- /widget-content -->

            </div>
        </div>

    </div>


</div>
