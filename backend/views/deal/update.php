<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Deal */

$this->title = Yii::t('lighttaj', 'Update {modelClass}: ', [
    'modelClass' => 'Deal',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('lighttaj', 'Deals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('lighttaj', 'Update');
?>
<div class="deal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
