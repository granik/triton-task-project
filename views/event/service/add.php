<?php 
$this->title = $title;
use app\widgets\CustomBreadcrumbs;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['event/main/show', 'eventId' => $event->id], $event->title],
        [[], 'Добавить доп. услугу']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?= $this->render('_form', array(
            'model' => $model,
            'cityItems' => $cityItems,
            'event' => $event,
            'method' => 'POST'
        )); ?>

    </div>
</div>