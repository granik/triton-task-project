<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InfoFields */

$this->title = 'Правка поля';
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item"><a href="/admin">Администрирование</a></li>
        <li class="breadcrumb-item"><a href="/admin/fields/webinars">Поля таблиц</a></li>
        <li class="breadcrumb-item active"><a href="#"><?= $this->title ?></a></li>
    </ol>
</nav>
<div class="info-fields-update row">
    <?= $this->render('../_side_menu')?>
    <div class="col-md-9">
        
        <?php if( Yii::$app->session->hasFlash('exists') ): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= Yii::$app->session->getFlash('exists'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <h3><?= Html::encode($this->title) ?></h3>
        <?= $this->render('_form', compact('model', 'type_opts', 'options')) ?>
    </div>
</div>