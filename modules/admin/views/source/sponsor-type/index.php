<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\widgets\CustomBreadcrumbs;

$this->title = $title;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['/event/main'], 'Главная'],
        [['/admin'], 'Администрирование'],
        [['/admin/sponsor-type'], 'Типы спонсоров']
    ]
]); ?>


<div class="sponsor-type-index row">
    <?= $this->render('../../_side_menu')?>
    <div class="col-md-9">
        <?php
        if( Yii::$app->session->hasFlash('success') ): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        <h3><?= Html::encode($this->title) ?></h3>
        <?php Pjax::begin(); ?>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a('Добавить тип', ['create'], 
                    ['class' => 'float-right mb-3 mr-2 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']) 
             ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute'=>'id',
                    'label'=>'#',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'id hidden-sm', 'style' => 'width: 50px'];
                    },
                ],
                [
                    'attribute'=>'name',
                    'label'=>'Имя',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'name'];
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'actions', 'style' => 'width: 250px'];
                    },
                    'template' => '{edit} {delete}',
                    'buttons' => [
                        'edit' => function ($url, $model) {
                            return Html::a(
                            'Правка', 
                            $url);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                            'Удалить',
                            $url,
                            [
                                'class' => 'text-danger',
                                'data' => [
                                    'confirm' => 'Удалить тип спонсора?',
                                    'method' => 'delete',
                                ],
                            ]);
                        },
                    ],
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'id', 'style' => 'width: 40px'];
                    },
                ], 
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
