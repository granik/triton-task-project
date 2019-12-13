<?php

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
        [[], 'Редактировать значение поля']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?= $this->render('_form', ['model' => $model, 'event' => $event, 'field' => $field, 'isMossem' => true]); ?>
    </div>
</div>