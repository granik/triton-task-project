<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LogisticFields */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logistic-fields-form">

    <?php $form = ActiveForm::begin([
        'method' => 'post'
    ]); ?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => 30]) ?>


    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
