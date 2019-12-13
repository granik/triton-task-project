<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;


$form = ActiveForm::begin([
    'validateOnBlur' => false,
    'method' => $method,
]);

        $params =
            [
                'prompt' => 'Выберите'
            ];

        echo $form->field($model, 'title')->textInput(['autofocus' => true, 'maxlength' => 200]);

        echo $form->field($model, 'category_id')->dropDownList($categoryItems, $params);

        echo $form->field($model, 'type_id')->dropDownList($typeItems) ;

        echo $form->field($model, 'type_custom')->textInput(['maxlength' => 20]);

        echo $form->field($model, 'date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Дата'],
            'removeButton' => false,
//                'convertFormat' => true,
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'bsVersion' => '4',
                'orientation' => 'top'
//                    'startDate'=> date('yyyy-mm-dd', strtotime(time()))artik

            ]
        ]);

        echo $form->field($model, 'city_id')->dropDownList($cityItems, $params);

        echo $form->field($model, 'notes')->textInput(['maxlength' => 30]);
         ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>