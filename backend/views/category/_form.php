<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use backend\assets\AssetManager;
/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="span12">
        <div class="span3">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?php //$form->field($model, 'file')->fileInput() ?>

            <label for="category-file" class="control-label">Icon</label>
            <div class="form-group field-category-file btn btn-warning">
                <i class="icon-large icon-upload"></i> Choose Icon
                <input type="hidden" value="" name="Category[file]"><input type="file" name="Category[file]" id="category-file">

                <div class="help-block"></div>
            </div>

        </div>
        <div class="span8">
            <?php

            if(!$model->isNewRecord && $model->hasIco){
                $categoryImage = AssetManager::URLForCategoryIconDirectory().'/'.$model->id.'_big.png';
                echo Html::tag('div',
                    Html::img ($categoryImage, [
                        'alt' => $model->name,
                        'style' => 'width:96px;margin:10px;'
                    ])
                );
            }

            ?>
         </div>
         <div class="span12">
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('lighttaj', 'Create') :
                    Yii::t('lighttaj', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?=Html::a(Yii::t('lighttaj','Back'), Url::toRoute('index'), ['class' => 'btn btn-invert']) ?>
            </div>
         </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<style>
 input[type="file"] {
     padding: 4px;
    }
</style>