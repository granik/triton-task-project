<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;
?>
<h5 class="pt-3 pb-1 text-center"><b>Электронные билеты</b></h5>

<?php Pjax::begin(['id' => 'event_tickets']); ?>

    <p>
        <?=
        Html::a(
            'Добавить билет',
            Url::toRoute(['event/ticket/create', 'eventId' => $event->id]),
            ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12',]
        );
        ?>
    </p>

<?= GridView::widget([
    'dataProvider' => $ticketDataProvider,
    'filterModel' => null,
    'tableOptions' => [
        'class' => 'table table-striped table-bordered pjax-reloadable font-resp'
    ],
    'columns' => [
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{download}',
            'buttons' => [
                    'download' => function($url, $model) use ($event) {
                        $link = Url::toRoute([
                            'app/download',
                            'eventId' => $event->id,
                            'name' => $model->filename]);
                        return "<span style='cursor:pointer' onclick='javascript:window.open(\"$link\")'>" . $model->filename . "</span>";
                    }
            ],
            'contentOptions' =>function ($model, $key, $index, $column){
                return ['class' => 'filename'];
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{up}{down}',
            'buttons' => [
                'up' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>',
                        '',
                        ['class' => 'gridViewAjaxLink btn btn-primary btn-sm gridViewSortTickets mr-1 mb-1',
                            'data-href' => "/event/ticket/up?id={$model->id}"
                        ]);
                },
                'down' => function ($url,$model) use ($event){
                    return Html::a(
                        '<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>',
                        '',
                        ['class' => 'gridViewAjaxLink btn btn-primary btn-sm gridViewSortTickets mb-1',
                            'data-href' => "/event/ticket/down?id={$model->id}"
                        ]);
                },
            ],
            'contentOptions' =>function ($model, $key, $index, $column){
                return ['style' => 'width: 90px'];
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url,$model) use ($event) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash text-danger" aria-hidden="true"></span>',
                        Url::toRoute(['event/ticket/delete', 'eventId' => $event->id, 'ticketId' => $model->id]),
                        [
                                'data' => [
                                'method' => 'DELETE',
                                'confirm' => 'Удалить билет?'
                            ]
                        ]
                        );
                },
            ],
            'contentOptions' =>function ($model, $key, $index, $column){
                return ['class' => '', 'style' => 'width: 30px;'];
            },
        ],
    ],
]); ?>
<?php Pjax::end(); ?>