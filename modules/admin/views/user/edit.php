<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
<div class="user-create row">
    <?= $this->render('../_side_menu')?>
    <div class="col-md-9">
        <h3><?= Html::encode($this->title) ?></h3>
        <div class="user-update-form">

            <?php $form = ActiveForm::begin([
              'method' => 'put',
            ]); ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'first_name')->textInput() ?>
            <?= $form->field($model, 'last_name')->textInput() ?>
            <?= $form->field($model, 'role_id')->radioList($roles, []) ?>
            <strong>Поменять пароль:</strong>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'password_repeat')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

