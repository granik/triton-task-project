<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\CustomBreadcrumbs;
/* @var $this yii\web\View */
/* @var $model app\models\SponsorType */

$this->title = $title;

$breadcrumbs = array();
$breadcrumbs[] = ['/', 'Главная'];
$breadcrumbs[] = ['/admin', 'Администрирование'];
$breadcrumbs[] = ['/admin/users', 'Пользователи'];
$breadcrumbs[] = ['/admin/user/view?id=' . $model->id, $model->fullname];
$breadcrumbs[] = ['/admin/user/update?id=' . $model->id, 'Править'];
echo CustomBreadcrumbs::widget(['content' => $breadcrumbs])

?>
<div class="user-create row">
    <?= $this->render('../_side_menu')?>
    <div class="col-md-9">
        <h3><?= Html::encode($this->title) ?></h3>
        <div class="user-update-form">

            <?php $form = ActiveForm::begin([
              'validateOnBlur' => false,
              'method' => 'post',
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

