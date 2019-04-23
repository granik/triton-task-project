<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\SponsorType */

$this->title = $title;


?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item"><a href="/admin">Администрирование</a></li>
        <li class="breadcrumb-item"><a href="/admin/users">Пользователи</a></li>
        <li class="breadcrumb-item active"><a href="#"><?= $this->title ?></a></li>
    </ol>
</nav>
<div class="user-list row">
    <?= $this->render('../_side_menu')?>
    <div class="col-md-9">
        <?php if( Yii::$app->session->hasFlash('success') ): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        <h3 class="mt-3"><?= Html::encode($this->title) ?></h3>
        <?php Pjax::begin(); ?>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a('Новый пользователь', ['create'], 
                    ['class' => 'float-right mb-3 mr-2 bg-primary p-1 text-center text-white d-block col-md-3 col-xs-12']) 
             ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['class' => 'font-resp table-users'],
            'columns' => [
                [
                    'attribute'=>'id',
                    'label'=>'#',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'id', 'style' => 'width: 50px'];
                    },
                ],
                [
                    'attribute'=>'fullname',
                    'label'=>'Имя',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'fullname'];
                    },
                ],
                [
                    'attribute'=>'email',
                    'label'=>'E-mail',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'email'];
                    },
                ],
                [
                    'attribute'=>'role.name',
                    'label'=>'Роль',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'role'];
                    },
                ],
                [
                    'attribute'=>'is_deleted',
                    'label'=>'Активен',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'role'];
                    },
                    'value' => function($model) {
                        return $model->is_deleted == 0 ? 'Да' : 'Нет';
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'actions', 'style' => 'width: 250px'];
                    },
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                            'Перейти', 
                            $url);
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

