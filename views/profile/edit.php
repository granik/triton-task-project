<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ProfileEditForm;
$this->title = $title;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item"><a href="/profile">Профиль</a></li>
        <li class="breadcrumb-item active"><a href="/profile/edit">Редактировать</a></li>
    </ol>
</nav>
<div class="row justify-content-center">
    <?= $this->render('_menu'); ?>
    <div class="col-sm-10">
    <h2 class="mt-2">Редактировать профиль</h2>
    <?php $form = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'post',
    ]); 
    echo $form->field($model, 'first_name')
            ->textInput();
    echo $form->field($model, 'last_name')
            ->textInput();
    echo $form->field($model, 'email')
            ->textInput();
    echo $form->field($model, 'current_password')
            ->passwordInput();
                    
   ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    </div>
</div>

