<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = $name;
?>

<div class="row">

    <div class="span12">

        <div class="error-container">
            <h1><?= Html::encode($this->title) ?></h1>

            <h2><?= nl2br(Html::encode($message))?></h2>

            <div class="error-details">
                Sorry, an error has occured! Why not try going back to the <a href="<?=Url::toRoute('/')?>">home page</a> or perhaps try following!

            </div> <!-- /error-details -->

            <div class="error-actions">
                <a href="<?=Url::toRoute('/')?>" class="btn btn-large btn-primary">
                    <i class="icon-chevron-left"></i>
                    &nbsp;
                    <?=Yii::t('lighttaj','Home')?>
                </a>



            </div> <!-- /error-actions -->

        </div> <!-- /error-container -->

    </div> <!-- /span12 -->

</div> <!-- /row -->
<style>
    .main{
        border-bottom: 0px;
    }
    .footer{
        display: none;
    }
</style>