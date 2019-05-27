<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InfoFields */

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item"><a href="/admin">Администрирование</a></li>
        <li class="breadcrumb-item"><a href="/admin/fields/mossem">Поля таблиц</a></li>
        <li class="breadcrumb-item active"><a href="#"><?= $this->title ?></a></li>
    </ol>
</nav>
<div class="info-fields-view row">
    <?= $this->render('../_side_menu')?>
    <div class="col-md-9">
        <h3 class="mt-2"><?= Html::encode($this->title) ?> (поле)</h3>
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
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'p-1 bg-danger  float-right text-white text-center mb-2 col-md-1 col-xs-12',
                'data' => [
                    'confirm' => 'Подтверждаете действие?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => 'ID',
                    'attribute' => 'id',
                ],
                [
                    'label' => 'Имя поля',
                    'attribute' => 'name',
                ],
                [
                    'label' => 'Тип поля',
                    'attribute' => 'type.name',
                ],
                [
                    'label' => 'С комментарием',
                    'attribute' => 'has_comment',
                    'value' => function($model) {
                        return $model->has_comment == 0 ? 'Нет' : 'Да';
                    }
                ],
                [
                    'label' => 'Опции',
                    'attribute' => 'options',
                    'value' => function($model) {
                    if(empty($model->options)) return;
                        $val = json_decode($model->options, true);
                        return implode(", ", $val);
                    }
                ],
            ],
        ]) ?>
    </div>
</div>
