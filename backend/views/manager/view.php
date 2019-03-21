<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\UserAdmin */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('lighttaj', 'User Admins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-admin-view">

    <div style="padding:0 0 20px 0">
        <?= Html::a(Yii::t('lighttaj', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php
        $session = Yii::$app->session;
        $session->open();
        $myUrl="";
        if ($session->has('manager_back')) $myUrl = $session->get('manager_back');
        echo Html::a(Yii::t('lighttaj','Back'), Url::toRoute('index').$myUrl,['class' => 'btn btn-invert']);
        ?>
    </div>

    <div class="widget widget-table action-table">
            <div class="widget-header">
                <i class="icon-user"></i>
                <h3><?= Html::encode($this->title) ?></h3>

            </div>
            <div class="widget-content">
        <?php
        if($model->status){
            $sts='<div style="color:#00ab30"><span title="Active" alt="Active" style="color:#00ab30" class="glyphicon glyphicon-ok"></span> Active</div>';
        }else{
            $sts='<div style="color:#ab2610"><span class="glyphicon glyphicon-remove" style="color:#ab2610" alt="Deactivated" title="Deactivated"></span> Deactivated</div>';

        }

        ?>
    <?= DetailView::widget([
        'model' => $model,
            'template'=>'<tr><td width="85px"><b>{label}</b></td><td>{value}</td></tr>',
            'options' => ['class' => 'table table-striped table-bordered'],

            'attributes' => [
            'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            [
                'attribute'=>'status',
                'label'=>'Status',
                'format'=>'raw',
                'value'=>$sts
            ]
            //'role',
            //'created_at',
            //'updated_at',
        ],
    ]) ?>
            </div>
        </div>
</div>
