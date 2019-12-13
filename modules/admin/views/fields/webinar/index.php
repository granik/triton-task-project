<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\widgets\CustomBreadcrumbs;

$this->title = $title;
$this->registerJs('$(".grid-view").addClass("font-resp")');
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['/event/main'], 'Главная'],
        [['/admin'], 'Администрирование'],
        [['/admin/fields/webinar'], 'Поля таблиц: вебинары'],
    ]
]); ?>
<div class="row">
    <?= $this->render('../../_side_menu')?>
    <div class="col-md-9">
        <h3><?= Html::encode($this->title) ?></h3>
        <?php if( Yii::$app->session->hasFlash('success') ): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= Yii::$app->session->getFlash('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php Pjax::begin(); ?>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a(
                    'Добавить поле', 
                    ['create'], 
                    ['class' => 'float-right mb-3 mr-2 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
            ) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                    [
                        'attribute'=>'id',
                        'filter' => '',
                        'label'=>'#',
                        'contentOptions' =>function ($model, $key, $index, $column){
                            return ['class' => 'id hidden-sm', 'style' => 'width: 50px'];
                        },
                    ],
                    [
                        'attribute'=>'name',
                        'label'=>'Название',
                        'contentOptions' =>function ($model, $key, $index, $column){
                            return ['class' => 'name'];
                        },
                    ],
                    [
                        'attribute'=>'type',
                        'label'=>'Тип',
                        'value' => 'type.name',
                        'contentOptions' =>function ($model, $key, $index, $column){
                            return ['class' => 'type'];
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' =>function ($model, $key, $index, $column){
                            return ['class' => 'position'];
                        },
                        'template' => '{up} {down}',
                        'buttons' => [
                            'up' => function ($url,$model) {
                                return Html::a(
                                '<img alt="up" src="/images/arrow-up.png" height="18">', 
                                '',
                                ['class' => 'btn btn-primary btn-sm gridViewAjaxLink',
                                  'data-href' => $url 
                                        ]);
                            },
                            'down' => function ($url,$model) {
                                return Html::a(
                                '<img alt="down" src="/images/arrow-down.png" height="18">', 
                                '',
                                ['class' => 'btn btn-primary btn-sm gridViewAjaxLink',
                                 'data-href' => $url
                                        ]);
                            },
                        ],
                        'contentOptions' =>function ($model, $key, $index, $column){
                            return ['style' => 'width: 110px'];
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' =>function ($model, $key, $index, $column){
                            return ['class' => 'name', 'style' => 'width: 250px'];
                        },
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url,$model) {
                                return Html::a(
                                'Перейти', 
                                $url);
                            },
                        ],
                        'contentOptions' =>function ($model, $key, $index, $column){
                            return ['class' => 'id', 'style' => 'width: 40px'];
                        },
                    ], 
                ]
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
