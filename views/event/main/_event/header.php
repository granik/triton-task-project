<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<?php if ($event->is_cancel): ?>
    <div class="alert alert-info" role="alert">
        Событие отменено!
    </div>
<?php elseif ($event->isPast):
    define('IS_PAST', true);
    ?>
    <div class="alert alert-primary" role="alert">
        Событие прошло <b><?= $event->date ?></b>
    </div>
<?php endif; ?>
<div class="col-lg-9 col-md-6 col-xs-12">
    <h6 class="pl-2 pr-2 mb-4 float-left"><?= Html::encode($event->title) ?></h6>
</div>
<div class="col-lg-3 col-md-6 col-xs-12 float-right pr-0 text-md-right text-center">
    <?=
    Html::a(
        '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>',
        Url::toRoute(['event/main/delete', 'eventId' => $event->id]),
        ['class' => 'm-1 pl-4 pr-4 btn btn-mg btn-danger',
            'data' => [
                'method' => 'delete',
                'confirm' => 'Удалить событие?'
            ]
        ]
    );
    ?>
    <?php
    if ($event->is_cancel) {
        echo Html::a(
            '<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>',
            Url::toRoute(['event/main/abort-cancel', 'eventId' => $event->id]),
            ['class' => 'pl-4 pr-4 m-1 btn btn-mg btn-success',
                'data' => [
                    'method' => 'post',
                    'confirm' => 'Вернуть событие?'
                ]
            ]
        );
    } else {
        echo Html::a(
            '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>',
            Url::toRoute(['event/main/cancel', 'eventId' => $event->id]),
            ['class' => 'pl-4 pr-4 m-1 btn btn-mg btn-warning',
                'data' => [
                    'method' => 'post',
                    'confirm' => 'Отменить событие?'
                ]
            ]
        );
    }
    ?>
    <?=

    Html::a(
        '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>',
        Url::toRoute([
            'event/main/edit',
            'eventId' => $event->id
        ]),
        ['class' => 'pl-4 pr-4 ml-1 btn btn-mg btn-primary']
    );
    ?>
</div>