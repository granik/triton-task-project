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
        [[], 'Добавить электронные билеты']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
    <?php $activeForm = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'POST',
    ]);
    echo "<i style='font-size: 0.7em'>Можно выбрать несколько файлов, удерживая CTRL</i>";
    echo $activeForm->field($model, 'ticket_file[]')->fileInput(['multiple' => true]);
    echo $activeForm->field($model, 'position')->hiddenInput(['value' => $nextPosition]);
    ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary d-block ml-auto mr-auto']) ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    </div>
</div>

