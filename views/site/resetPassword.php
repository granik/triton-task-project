<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
 
$this->title = 'Восстановление пароля';
?>
 
<div class="site-reset-password">
    <h3 class="mt-3"><?= Html::encode($this->title) ?></h3>
    <p>Введите новый пароль:</p>
    <div class="row">
        <div class="col-lg-5">
 
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'password_repeat')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
 
        </div>
    </div>
</div>