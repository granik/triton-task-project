<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\widgets\CustomBreadcrumbs;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Города';
$this->params['breadcrumbs'][] = $this->title;
 ?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['/event/main'], 'Главная'],
        [['/admin'], 'Администрирование'],
        [['/admin/city'], 'Города']
    ]
]); ?>
<div class="row">
    <?= $this->render('../../_side_menu')?>
    <div class="city-index col-md-9">
        <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <h3><?= Html::encode($this->title) ?></h3>
        <?php Pjax::begin(); ?>

        <p>
            <?= Html::a('Добавить город', ['create'], ['class' => 'p-2 bg-primary text-white text-center float-right mb-2 col-md-2 col-xs-12']) ?>
        </p>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
//            'pager' => ['class' => yii\data\Pagination::className()],
            'layout'=>"{summary}\n{items}\n{pager}",
            'columns' => [
                [
                    'attribute'=>'id',
                    'label'=>'#',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'id', 'style' => 'width: 50px'];
                    },
                ],
                [
                    'attribute'=>'name',
                    'label'=>'Название города',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'name'];
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'name', 'style' => 'width: 250px'];
                    },
                    'template' => '{edit} {delete}',
                    'buttons' => [
                        'edit' => function ($url,$model) {
                            return Html::a(
                            'Правка', 
                            $url);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('Удалить', $url, [
                                'class' => 'text-danger',
                                'data' => [
                                    'confirm' => 'Удалить город?',
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
