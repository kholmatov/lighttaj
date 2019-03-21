<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Deal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'categoryID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lat')->textInput() ?>

    <?= $form->field($model, 'lon')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'priceType')->textInput() ?>

    <?= $form->field($model, 'priceSale')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'priceRegular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'offDollar')->textInput() ?>

    <?= $form->field($model, 'offPercent')->textInput() ?>

    <?= $form->field($model, 'units')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'benefit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dateCreated')->textInput() ?>

    <?= $form->field($model, 'dateEnding')->textInput() ?>

    <?= $form->field($model, 'storeName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'storeAddress')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('lighttaj', 'Create') : Yii::t('lighttaj', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
