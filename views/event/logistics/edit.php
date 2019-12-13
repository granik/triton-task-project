<?php
use yii\helpers\Html;
use app\widgets\CustomBreadcrumbs;
$this->title = $title;
$this->registerJs('$(".mask-time").mask("99:99");', 
    \yii\web\View::POS_END
        );
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['event/main/show', 'eventId' => $event->id], $event->title],
        [[], 'Редактировать логистическую информацию']
    ]
]); ?>
<div class="row justify-content-end">
<?=
    Html::a(
        'Удалить строку',
        '',
        ['class' => 'float-right mb-3 bg-danger p-1 text-center text-white d-block col-md-2 col-xs-12 m-3',
            'data' => [
                'method' => 'post',
                'confirm' => 'Удалить строку?',
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
        <?= $this->render('_form',
            [
                'fields' => $fields,
                'means' => $means,
                'model' => $model,
                'event' => $event,
                'method' => 'PUT'
            ]); ?>
    </div>
</div>

