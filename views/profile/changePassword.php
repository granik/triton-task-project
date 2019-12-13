<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ProfileEditForm;
use app\widgets\CustomBreadcrumbs;
$this->title = $title;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'Главная'],
        [['profile'], 'Профиль'],
        [[], 'Смена пароля']
    ]
]); ?>
<div class="row justify-content-center">
    <?= $this->render('_menu'); ?>
    <div class="col-sm-10">
    <h2 class="mt-2">Сменить пароль</h2>
    <?php $form = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'patch',
    ]); 
    echo $form->field($model, 'old_password')
            ->passwordInput();
    echo $form->field($model, 'new_password')
            ->passwordInput();
    echo $form->field($model, 'password_repeat')
            ->passwordInput();
                    
   ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Сменить пароль',
                ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button'])
            ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    </div>
</div>

