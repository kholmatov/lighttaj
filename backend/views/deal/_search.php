<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DealSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userID') ?>

    <?= $form->field($model, 'categoryID') ?>

    <?= $form->field($model, 'lat') ?>

    <?= $form->field($model, 'lon') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'priceType') ?>

    <?php // echo $form->field($model, 'priceSale') ?>

    <?php // echo $form->field($model, 'priceRegular') ?>

    <?php // echo $form->field($model, 'offDollar') ?>

    <?php // echo $form->field($model, 'offPercent') ?>

    <?php // echo $form->field($model, 'units') ?>

    <?php // echo $form->field($model, 'benefit') ?>

    <?php // echo $form->field($model, 'dateCreated') ?>

    <?php // echo $form->field($model, 'dateEnding') ?>

    <?php // echo $form->field($model, 'storeName') ?>

    <?php // echo $form->field($model, 'storeAddress') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('lighttaj', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('lighttaj', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
