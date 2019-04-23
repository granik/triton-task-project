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
$breadcrumbs[] = ['/admin/user/create', 'Новый'];
echo CustomBreadcrumbs::widget(['content' => $breadcrumbs])

?>
<div class="user-create row">
    <?= $this->render('../_side_menu')?>
    <div class="col-md-9">
        <h3><?= Html::encode($this->title) ?></h3>
        <p>Заполните следующие поля:</p>
        <div class="sponsor-type-form">

            <?php $form = ActiveForm::begin([
              'validateOnBlur' => false,
              'method' => 'post',
            ]); ?>

            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'first_name')->textInput() ?>
            <?= $form->field($model, 'last_name')->textInput() ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'password_repeat')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

