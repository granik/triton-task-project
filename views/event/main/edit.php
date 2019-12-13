<?php

use app\widgets\CustomBreadcrumbs;

$this->title = $title;
$method = 'PUT';
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['event/main/show', 'eventId' => $event->id], $event->title],
        [[], 'Редактировать']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?= $this->render('_form',
            compact('model', 'typeItems', 'categoryItems', 'cityItems', 'method')
        ); ?>
    </div>
</div>
