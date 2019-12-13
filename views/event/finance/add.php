<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\CustomBreadcrumbs;
$this->title = $title;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['event/main/show', 'eventId' => $event->id], $event->title],
        [[], 'Добаавить финансовую информацию']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
    <?php $activeForm = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'POST',
    ]); 
    echo $activeForm->field($form, 'event_id')
            ->hiddenInput(['value' => $event['id'] ]);
    echo $activeForm->field($form, 'type_id')
            ->dropDownList($fields, ['prompt' => 'Выберите']);
    echo $activeForm->field($form, 'exist')
            ->radioList([0 => 'Нет', 1 => 'Да'], ['value' => 0]);
    echo $activeForm->field($form, 'status')
            ->radioList([0 => 'Не оплачен', 1 => 'Оплачен'], ['value' => 0]);
    
    echo $activeForm->field($form, 'comment')
            ->textarea();
                    
   ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    </div>
</div>

