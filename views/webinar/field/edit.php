<?php 
$this->title = $title;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\widgets\CustomBreadcrumbs;
use yii\helpers\Url;
$this->registerJs('$(".mask-time").mask("99:99");', 
    \yii\web\View::POS_END
        );
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['webinar/main/show', 'webinarId' => $event->id], $event->title],
        [[], 'Редактировать значение поля']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
    <?php $form = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'PUT',
    ]); 
    $fieldType = $field->type['name'];
    if($fieldType === 'radio') {
        $items = Json::decode($field['options']);
        $items = array_combine($items, $items);
        echo $form->field($model, 'value')
                ->radioList($items);
    } elseif ($fieldType === 'text') {
        echo $form->field($model, 'value')
                ->textInput();
        
    } elseif($fieldType === 'select') {
        $opts = Json::decode($field->options);
        $opts = array_combine($opts, $opts);
        echo $form->field($model, 'value')
                ->dropDownList($opts);

    } elseif($fieldType === 'file') {
        echo $form->field($model, 'file_single')
                ->fileInput();
        
    } elseif($fieldType === 'time') {
        echo $form->field($model, 'value')
                ->textInput(['class' => 'mask-time']);
    } elseif($fieldType === 'date') {
        echo $form->field($model, 'value')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
//                'convertFormat' => true,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
                ]
            ]);
    }
    if($field->has_comment) {
        echo $form->field($model, 'comment')
                ->textInput();
    }
    
    ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    <?php
    if(isset($field->field)) {
        echo Html::a("Очистить поле",
            Url::toRoute(['webinar/field/clear', 'webinarId' => $event->id, 'fieldId' => $field->id]),
            [
                'class' => 'btn btn-outline-danger d-block ml-auto mr-auto mb-3',
                'data' => [
                    'method' => 'POST',
                    'confirm' => 'Очистить данные?'
                ]
            ]);
    }
    ?>

    </div>
</div>