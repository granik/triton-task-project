<?php 

use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Html;

$form = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => $method,
    ]); 
    echo $form->field($model, 'event_id')
            ->hiddenInput(['value' => $event['id'] ]);
    echo $form->field($model, 'type_id')
            ->dropDownList($fields, ['prompt' => 'Выберите']);
    echo $form->field($model, 'persons')
            ->textInput(['maxlength' => 50]);
    echo $form->field($model, 'to_means')
           ->dropDownList($means, ['prompt' => 'Выберите']);
    echo $form->field($model, 'to_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
                    
        ]
    ]);
    echo $form->field($model, 'to_time')
            ->textInput(['maxlength' => 5, 'class' => 'mask-time form-control']);
    echo $form->field($model, 'to_arrival')
            ->textInput(['maxlength' => 5, 'class' => 'mask-time form-control']);
    echo $form->field($model, 'living_from')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
                    
        ]
    ]);
    
    echo $form->field($model, 'living_to')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
        ]
    ]);
    
    echo $form->field($model, 'between_means')
           ->dropDownList($means, ['prompt' => 'Выберите']);
    echo $form->field($model, 'between_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'bsVersion' => '4',
                    
        ]
    ]);
    
    echo $form->field($model, 'between_time')
            ->textInput(['maxlength' => 5, 'class' => 'mask-time form-control']);
    echo $form->field($model, 'between_arrival')
            ->textInput(['maxlength' => 5, 'class' => 'mask-time form-control']);
    
    echo $form->field($model, 'home_means')
           ->dropDownList($means, ['prompt' => 'Выберите']);
    echo $form->field($model, 'home_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
                    
        ]
    ]);
    echo $form->field($model, 'home_time')
            ->textInput(['maxlength' => 5, 'class' => 'mask-time form-control']);
    
    echo $form->field($model, 'home_arrival')
            ->textInput(['maxlength' => 5, 'class' => 'mask-time form-control']);
    ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
        </div>
    </div>
<?php ActiveForm::end();?>
