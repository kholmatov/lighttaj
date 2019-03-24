<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="navbar navbar-fixed-top">

    <div class="navbar-inner">

        <div class="container">

            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <a class="brand" href="/">
                lighttaj
            </a>
        </div> <!-- /container -->

    </div> <!-- /navbar-inner -->

</div> <!-- /navbar -->

<div class="account-container">

    <div class="content clearfix">

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>


        <h1>Member Login</h1>

            <div class="login-fields">

                <p>Please provide your details</p>

                <div class="field">
                    <label for="username">Username</label>
                    <?= $form->field($model, 'username')->textInput(['id'=>'username','autofocus' => true,'class' => 'login username-field','placeholder'=>'Username']) ?>

                </div> <!-- /field -->

                <div class="field">
                    <label for="password">Password:</label>
                    <?= $form->field($model, 'password')->passwordInput(['id'=>'password','placeholde'=>'Password','class'=>'login password-field']) ?>
                </div> <!-- /password -->

            </div> <!-- /login-fields -->

            <div class="login-actions">

				<span class="login-checkbox">
                     <?= $form->field($model, 'rememberMe')->checkbox(['class'=>'field login-checkbox']) ?>
				</span>

<!--                <button class="button btn btn-success btn-large">Sign In</button>-->
                <?= Html::submitButton('Login', ['class' => 'button btn btn-success btn-large', 'name' => 'login-button']) ?>

            </div> <!-- .actions -->



        <?php ActiveForm::end(); ?>

    </div> <!-- /content -->

</div> <!-- /account-container -->
<!--<div class="login-extra">-->
<!--    <a href="login.html#">Reset Password</a>-->
<!--</div> -->
<!-- /login-extra -->
<?php
$this->registerJsFile(Yii::$app->request->baseUrl.'/them/js/jquery-1.7.2.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/them/js/bootstrap.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/them/js/signin.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>

