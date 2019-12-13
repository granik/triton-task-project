<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\CustomBreadcrumbs;
use yii\helpers\Url;

$this->title = $title;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['event/main/show', 'eventId' => $event->id], $event->title],
        [[], 'Редактировать финансовую информацию']
    ]
]); ?>
<div class="row justify-content-end">
<?=
    Html::a(
        'Удалить строку',
        Url::toRoute(['event/finance/delete', 'eventId' => $event->id, 'itemId' => $model->id]),
        ['class' => 'float-right mb-3 bg-danger p-1 text-center text-white d-block col-md-2 col-xs-12 m-3',
            'data' => [
                'method' => 'delete',
                'confirm' => 'Удалить строку из таблицы?',
                'params' => [
                    'remove' => 1
                ]
                
            ]
        ]
    ); 
?>
</div>
<div class="row justify-content-center">

    <div class="col-sm-5">
    <?php $activeForm = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'PUT',
    ]); 
    echo $activeForm->field($model, 'event_id')
            ->hiddenInput();
    echo $activeForm->field($model, 'type_id')
            ->dropDownList($fields, ['prompt' => 'Выберите']);
    echo $activeForm->field($model, 'exist')
            ->radioList([0 => 'Нет', 1 => 'Да']);
    echo $activeForm->field($model, 'status')
            ->radioList([0 => 'Не оплачен', 1 => 'Оплачен']);
    
    echo $activeForm->field($model, 'comment')
            ->textarea();
                    
   ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    </div>
</div>

