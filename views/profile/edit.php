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
        [['profile/index'], 'Профиль'],
        [[], 'Редактировать']
    ]
]); ?>
<div class="row justify-content-center">
    <?= $this->render('_menu'); ?>
    <div class="col-sm-10">
    <h2 class="mt-2">Редактировать профиль</h2>
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('error'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <?php $form = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'put',
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
            <?= Html::submitButton('Сохранить',
                ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button'])
            ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    </div>
</div>

