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
                <input type="hidden" value="" name="Category[file]">
                <input type="file" onchange="readURL(this);" name="Category[file]" id="category-file">

                <div class="help-block"></div>
            </div>
            <div class="alert myinfo" style="display:none"></div>
            <div class="alert alert-info">
                <button data-dismiss="alert" class="close" type="button">×</button>
                Must be 96x96 pix, PNG
            </div>


        </div>
        <div class="span8">
            <?php

            if(!$model->isNewRecord && $model->hasIco){
                $categoryImage = AssetManager::URLForCategoryIconDirectory().'/'.$model->id.'_big.png?rnd='.rand(0,100);
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
                    Yii::t('lighttaj', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
                ?>

                <?php
                if($model->status) {
                    echo Html::a (Yii::t ('lighttaj', 'Deactivate'), ['deactivate', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t ('lighttaj', 'Are you sure you want to deactivate this category?'),
                            'method' => 'post',
                        ],
                    ]);
                }else{
                    if($model->id) {
                        echo Html::a (Yii::t ('lighttaj', 'Activate'), ['activate', 'id' => $model->id], [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => Yii::t ('lighttaj', 'Are you sure you want to activate this category?'),
                                'method' => 'post',
                            ],
                        ]);
                    }
                }
                ?>
<!--                Yii::$app->request->referrer-->
                <?php
                $session = Yii::$app->session;
                $session->open();
                $myUrl="";
                if ($session->has('category_back')) $myUrl = $session->get('category_back');
                echo Html::a(Yii::t('lighttaj','Back'), Url::toRoute('index').$myUrl,['class' => 'btn btn-invert']);
                ?>


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
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
//                $('#myico')
//                    .attr('src', e.target.result)
//                    .width(96)
//                    .height(96);
                var filename = $('input[type=file]').val().split('\\').pop();
                $('.myinfo').html(filename);
                $('.myinfo').show();
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>