<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Deal */

$this->title = Yii::t('lighttaj', 'Create Deal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('lighttaj', 'Deals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
