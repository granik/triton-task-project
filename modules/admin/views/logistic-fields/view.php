<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LogisticFields */

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item"><a href="/admin">Администрирование</a></li>
        <li class="breadcrumb-item"><a href="/admin/fields/logistics">Поля таблиц</a></li>
        <li class="breadcrumb-item active"><a href="#"><?= $this->title ?></a></li>
    </ol>
</nav>
<div class="logistic-fields-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Правка', ['update', 'id' => $model->id], ['class' => 'p-1 ml-3 mb-1 bg-primary text-white text-center col-md-1 float-right offset-md-9 col-xs-12']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id,], [
            'class' => 'p-1 bg-danger  float-right text-white text-center mb-2 col-md-1 col-xs-12',
            'data' => [
                'confirm' => 'Удалить поле?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name'
        ],
    ]) ?>

</div>
