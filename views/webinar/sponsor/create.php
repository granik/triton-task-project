<?php

use app\widgets\CustomBreadcrumbs;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = $title;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['webinar/main/show', 'webinarId' => $webinar->id], $webinar->title],
        [[], 'Добавить спонсора']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
    <?php $active_form = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'post',
    ]); 
    echo $active_form->field($form, 'event_id')
            ->hiddenInput(['value' => $webinar->id]);
    echo $active_form->field($form, 'name')
            ->textInput(['autofocus' => true, 'maxlength' => 50]);
    echo $active_form->field($form, 'type_id')
            ->dropDownList($typeOpts, ['prompt' => 'Выберите']);
    ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    </div>
</div>
