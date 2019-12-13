<?php
$this->title = $title;
use app\widgets\CustomBreadcrumbs;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['event/main/show', 'eventId' => $event->id], $event->title],
        [[], 'Добавить спонсора']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
    <?php $activeForm = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'POST',
    ]); 
    echo $activeForm->field($form, 'event_id')
            ->hiddenInput(['value' => $event->id]);
    echo $activeForm->field($form, 'name')
            ->textInput(['autofocus' => true, 'maxlength' => 50]);
    echo $activeForm->field($form, 'type_id')
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

