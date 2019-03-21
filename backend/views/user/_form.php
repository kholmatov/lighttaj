<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'confirmedEmail')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('lighttaj', 'Create') : Yii::t('lighttaj', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php
        $session = Yii::$app->session;
        $session->open();
        $myUrl="";
        if ($session->has('user_back')) $myUrl = $session->get('user_back');
        echo Html::a(Yii::t('lighttaj','Back'), Url::toRoute('index').$myUrl,['class' => 'btn btn-invert']);
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
