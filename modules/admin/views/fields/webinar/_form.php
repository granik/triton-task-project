<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InfoFields */
/* @var $form yii\widgets\ActiveForm */
?>

       
<div class="info-fields-form">

    <?php $form = ActiveForm::begin([
        'validateOnBlur' => false,
        'method' => $method,
    ]); ?>



    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'type_id')->dropDownList($type_opts, ['style' => 'width: 200px']) ?>

    <?= $form->field($model, 'has_comment')->checkbox() ?>

    <strong>Опции(если требуется):</strong>
    <i>Для селектов, чекбоксов и радиокнопок</i>
    <?php
    $config = array(0 => null);
    for ($i=1; $i<=5; $i++) {
        $config[] = ['value' => !empty($options[(string)$i]) ? $options[(string)$i] : null, 'placeholder' => $i];
    }
    ?>
    <?= $form->field($model, 'option1')->textInput($config[1]) ?>
    <?= $form->field($model, 'option2')->textInput($config[2]) ?>
    <?= $form->field($model, 'option3')->textInput($config[3]) ?>
    <?= $form->field($model, 'option4')->textInput($config[4]) ?>
    <?= $form->field($model, 'option5')->textInput($config[5]) ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary mt-5']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
