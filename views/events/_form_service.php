<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
?>

<div class="add-service-form">

    <?php $form = ActiveForm::begin([
        'method' => 'post'
    ]); ?>

    <?= $form->field($model, 'event_id')->hiddenInput([ 'value' => $event->id ]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 25]) ?>
    <?= $form->field($model, 'customer')->textInput(['maxlength' => 25]) ?>
    <?= $form->field($model, 'producer')->textInput(['maxlength' => 25]) ?>
    <?= $form->field($model, 'city_id')->dropDownList($city_items) ?>
    <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
//                'convertFormat' => true,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
//                    'startDate'=> date('yyyy-mm-dd', strtotime(time()))artik
                    
                ]
            ]);
    ?>
    <?= $form->field($model, 'people_amount')->textInput(['maxlength' => 5]) ?>
    <?= $form->field($model, 'status')->radioList([0 => 'Нет', 1 => 'Да']) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
