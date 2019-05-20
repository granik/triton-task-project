<?php 
$this->title = $title;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
$this->registerJs('$(".mask-time").mask("99:99");', 
    \yii\web\View::POS_END
        );
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item"><a href="/event/<?= $event['id'] ?>"><?= $event['title'] ?></a></li>
        <li class="breadcrumb-item active"><a href="#">Редактирование</a></li>
    </ol>
</nav>
<div class="row justify-content-center">
    <div class="col-sm-5">
    <?php $form = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'post',
    ]); 
    $fieldType = $field['type']['name'];
    if($fieldType === 'radio') {
        $items = Json::decode($field['options']);
        $items = array_combine($items, $items);
        echo $form->field($model, 'value')
                ->radioList($items);
        if(null != $model->value) {
            echo Html::a("Очистить поле", 
                    "/webinars/truncate-field?webinar_id={$model->webinar_id}&field_id={$model->field_id}",
                            [
                                'class' => 'btn btn-outline-danger d-block ml-auto mr-auto mb-3',
                                'data' => [
                                    'method' => 'POST',
                                    'confirm' => 'Очистить данные?'
                                ]
                            ]);
        }
    } elseif ($fieldType === 'text') {
        echo $form->field($model, 'value')
                ->textInput();
        
    } elseif($fieldType === 'select') {
        $opts = Json::decode($field['options']);
        $opts = array_combine($opts, $opts);
        echo $form->field($model, 'value')
                ->dropDownList($opts);
        if(null != $model->value) {
            echo Html::a("Очистить поле", 
                    "/webinars/truncate-field?webinar_id={$model->webinar_id}&field_id={$model->field_id}",
                            [
                                'class' => 'btn btn-outline-danger d-block ml-auto mr-auto mb-3',
                                'data' => [
                                    'method' => 'POST',
                                    'confirm' => 'Очистить данные?'
                                ]
                            ]);
        }
    } elseif($fieldType === 'file') {
        echo $form->field($model, 'file_single')
                ->fileInput();
        if(null != $model->value) {
            echo Html::a("Удалить загруженный файл", 
                "/webinars/unlink-file?webinar_id={$model->webinar_id}&field_id={$model->field_id}",
                        [
                            'class' => 'btn btn-outline-danger d-block ml-auto mr-auto mb-3',
                            'data' => [
                                'method' => 'POST',
                                'confirm' => 'Удалить ранее загруженный файл?'
                            ]
                        ]);
        }
        
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
//                    'startDate'=> date('yyyy-mm-dd', strtotime(time()))artik
                    
                ]
            ]);
    }
    
    if($field['has_comment']) {
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

    </div>
</div>