<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InfoFields */

$this->title = $model->fullname;
\yii\web\YiiAsset::register($this);
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item"><a href="/admin">Администрирование</a></li>
        <li class="breadcrumb-item"><a href="/admin/users">Пользователи</a></li>
        <li class="breadcrumb-item active"><a href="#"><?= $this->title ?></a></li>
    </ol>
</nav>
<div class="user-view row">
    <?= $this->render('../_side_menu')?>
    <div class="col-md-9">
        <h3 class="mt-2"><?= Html::encode(implode(' ', [$model->first_name, $model->last_name]) ) ?></h3>
        <?php if( Yii::$app->session->hasFlash('success') ): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        <p>
            <?= Html::a('Правка', ['update', 'id' => $model->id], ['class' => 'p-1 ml-3 mb-1 bg-primary text-white text-center col-md-1 float-right offset-md-9 col-xs-12']) ?>
            <?php if ($model->id != 1): ?>
            <?php if($model->is_deleted == 0): ?>
            <?= Html::a('Деактивировать', ['delete', 'id' => $model->id], [
                'class' => 'p-1 bg-danger  float-right text-white text-center mb-2 col-md-2 col-xs-12',
                'data' => [
                    'confirm' => 'Подтверждаете действие?',
                    'method' => 'delete',
                ],
            ]) ?>
            <?php else: ?>
            <?= Html::a('Активировать', ['restore', 'id' => $model->id], [
                'class' => 'p-1 bg-success  float-right text-white text-center mb-2 col-md-2 col-xs-12',
                'data' => [
                    'confirm' => 'Подтверждаете действие?',
                    'method' => 'post',
                ],
            ]) ?>
            <?php endif; ?>
            <?php endif; ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => 'ID',
                    'attribute' => 'id',
                ],
                [
                    'label' => 'Имя',
                    'attribute' => 'first_name',
                ],
                [
                    'label' => 'Фамилия',
                    'attribute' => 'last_name',
                ],
                [
                    'label' => 'E-mail',
                    'attribute' => 'email',
                ],
                [
                    'label' => 'Роль',
                    'attribute' => 'role.name',
                ],
                [
                    'label' => 'Активен',
                    'attribute' => 'is_deleted',
                    'value' => function($model) {
                        return $model->is_deleted == 0 ? 'Да' : 'Нет';
                    }
                ],
            ],
        ]) ?>
    </div>
</div>
