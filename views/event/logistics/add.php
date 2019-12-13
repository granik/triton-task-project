<?php
$this->title = $title;
$this->registerJsFile('/web/js/jquery.maskedinput.min.js', [
    'position' => \yii\web\View::POS_BEGIN,
        ]
        );
$this->registerJs('$(".mask-time").mask("99:99");', 
    \yii\web\View::POS_END
        );
use app\widgets\CustomBreadcrumbs;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['event/main/show', 'eventId' => $event->id], $event->title],
        [[], 'Добавить логистическую информацию']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?= $this->render('_form',
            [
                'model' => $form,
                'means' => $means,
                'fields' => $fields,
                'event' => $event,
                'method' => 'POST'
            ]
        ); ?>
    </div>
</div>

