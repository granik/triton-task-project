<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

$form = ActiveForm::begin([
    'validateOnBlur' => false,
    'method' => 'PUT',
]);
        $fieldType = $field->type['name'];
        switch($fieldType) {
            case 'radio': {
                $items = Json::decode($field->options);
                $items = array_combine($items, $items);
                echo $form->field($model, 'value')
                    ->radioList($items);
            } break;
            case 'text': {
                echo $form->field($model, 'value')
                    ->textInput();
            } break;
            case 'select': {
                $opts = Json::decode($field['options']);
                $opts = array_combine($opts, $opts);
                echo $form->field($model, 'value')
                    ->dropDownList($opts);
            } break;
            case 'file': {
                echo $form->field($model, 'file_single')
                    ->fileInput();
            } break;
            case 'time': {
                echo $form->field($model, 'value')
                    ->textInput(['class' => 'mask-time']);
            } break;
            case 'date': {
                echo $form->field($model, 'value')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Дата'],
                    'removeButton' => false,
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'bsVersion' => '4',
                        'orientation' => 'top'

                    ]
                ]);
            } break;

            default:
                throw new \Exception('Unexpected field type value');
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
if (null != $model->value && $fieldType === 'file') {
    echo Html::a("Удалить загруженный файл",
        Url::toRoute(["event/field/unlink-file", 'eventId' => $event->id, 'fieldId' => $field->id] ),
        [
            'class' => 'btn btn-lg btn-outline-danger d-block ml-auto mr-auto mb-3',
            'data' => [
                'method' => 'DELETE',
                'confirm' => 'Удалить загруженный файл?'
            ]
        ]);
}
if($fieldType !== 'file' && isset($field->field)) {
    $clearRoute = isset($isMossem) ? ['event/field/clear-mossem', 'mossemId' => $event->id, 'fieldId' => $field->id] :
        ['event/field/clear', 'eventId' => $event->id, 'fieldId' => $field->id];

    echo Html::a("Очистить поле",
        Url::toRoute($clearRoute),
        [
            'class' => 'btn btn-lg btn-outline-danger d-block ml-auto mr-auto mb-3',
            'data' => [
                'method' => 'POST',
                'confirm' => 'Очистить данные?'
            ]
        ]);
}
?>
