<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
?>
<h5 class="pt-3 pb-1 text-center"><b>Электронные билеты</b></h5>

<?php Pjax::begin(['id' => 'event_tickets']); ?>

    <p>
        <?=
        Html::a(
            'Добавить билет',
            '/event/' . $event['id'] . '/add-ticket',
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
            'attribute'=>'filename',
            'label'=>'Имя файла',
            'contentOptions' =>function ($model, $key, $index, $column){
                return ['class' => 'filename'];
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{up}{down}',
//            'contentOptions' => ['class' => 'p-0'],
            'buttons' => [
                'up' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>',
                        '',
                        ['class' => 'btn btn-primary btn-sm gridViewSortTickets mr-1 mb-1',
                            'data-href' => 'ticket-up?id=' . $model->id
                        ]);
                },
                'down' => function ($url,$model) use ($event){
                    return Html::a(
                        '<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>',
                        '',
                        ['class' => 'btn btn-primary btn-sm gridViewSortTickets mb-1',
                            'data-href' => 'ticket-down?event_id=' . $event['id'] . '&ticket_id=' . $model->id
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
                        '/event/' . $event['id'] . '/delete-ticket/' . $model->id,
                        ['data' => [
                                'method' => 'POST',
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