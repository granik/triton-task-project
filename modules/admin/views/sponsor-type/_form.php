<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SponsorType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sponsor-type-form">

    <?php $form = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'post',
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 20]) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
