<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\UserAdmin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-admin-form">


            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            <?php
            /*
                <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>
                */
            ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?php
            if(!$model->id) {
                echo $form->field ($model, 'password_hash')->passwordInput();
            }
            ?>


                <?php
                if($model->id != \Yii::$app->user->identity->id) {
//                    echo $form->field ($model, 'status')->checkbox (['disabled' => 'disbled']);
//                }else{
                    echo '<label class="checkbox inline">';
                    echo $form->field ($model, 'status')->checkbox ();
                    echo '</label>';
                }
                ?>







    <?php
    /*
             <?= $form->field($model, 'role')->textInput() ?>
            <?= $form->field($model, 'created_at')->textInput() ?>

            <?= $form->field($model, 'updated_at')->textInput() ?>
    */
    ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('lighttaj', 'Create') : Yii::t('lighttaj', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?php
                $session = Yii::$app->session;
                $session->open();
                $myUrl="";
                if ($session->has('manager_back')) $myUrl = $session->get('manager_back');
                echo Html::a(Yii::t('lighttaj','Back'), Url::toRoute('index').$myUrl,['class' => 'btn btn-invert']);
                ?>
            </div>

         <?php ActiveForm::end(); ?>



</div>
