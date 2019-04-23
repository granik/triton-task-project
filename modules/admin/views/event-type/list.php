<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы событий';
$this->params['breadcrumbs'][] = $this->title;
 ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item"><a href="/admin">Администрирование</a></li>
        <li class="breadcrumb-item active"><a href="/admin/event-type">Типы событий</a></li>
        <!--<li class="breadcrumb-item active"><a href="/event/add">Добавить событие</a></li>-->
    </ol>
</nav>
<div class="row">
    <?= $this->render('../_side_menu')?>
    <div class="event-type-index col-md-9">
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
            <?= Html::a('Новая запись', ['create'], ['class' => 'p-2 bg-primary text-white text-center float-right mb-2 col-md-2 col-xs-12']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
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
                    'label'=>'Название',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'name'];
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'name', 'style' => 'width: 250px'];
                    },
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'update' => function ($url,$model) {
                            return Html::a(
                            'Правка', 
                            $url);
                        },
                        'delete' => function ($url,$model,$key) {
                            return Html::a('Удалить', $url, [
                                'class' => 'text-danger',
                                'data' => [
                                    'confirm' => 'Удалить тип события?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'id', 'style' => 'width: 30px'];
                    },
                ],  
            ],

        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
