<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('lighttaj', 'Admin list');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-admin-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="span3" style="padding:0 0 20px 0">
            <?=Html::a(Yii::t('lighttaj', 'Create new Admin'), ['create'], ['class' => 'btn btn-success']);?>
        </div>
    </div>

    <div class="widget widget-nopad mywiget">

    <?php

    $title=Html::encode($this->title);
    $mySearch='<div style="float:right;margin:3px 5px 0 0">
                        <form action="" method="GET" class="filters">
                            <input type="text" name="UserAdminSearch[searchstring]" placeholder="Search" value="'.$searchModel->searchstring.'" class="form-control">
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
                'label' => 'Username',
                'attribute'=>'username',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        $data->username,
                        Url::toRoute(['/manager/view','id'=>$data->id]),
                        [
                            'title' => 'view',
                            //'target' => '_blank'
                        ]
                    );
                },
                // 'headerOptions' => ['width'=>'80']
            ],
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
             'email:email',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data){
                    if($data->status!=0)
                        return '<center style="color:#00ab30"><span title="Active" alt="Active" style="color:#00ab30" class="glyphicon glyphicon-ok"></span> Active</center>';
                    else
                        return '<center style="color:#ab2610"><span title="Deactivated" alt="Deactivated" style="color:#ab2610" class="glyphicon glyphicon-remove"></span> Deactivated</center>';
                },
                'headerOptions' => ['width' => '100']
            ],
            // 'status',
            // 'role',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn','headerOptions' => ['width' => '30']],
        ],
    ]); ?>

                </div>
                <!-- /widget-content -->

            </div>
        </div>

    </div>
</div>
