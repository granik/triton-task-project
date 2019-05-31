<?php 
$this->title = $title;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item active"><a href="#">Установить итоговую явку</a></li>
    </ol>
</nav>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?php $form = ActiveForm::begin([
                        'validateOnBlur' => false,
                        'method' => 'post',
                      ]);
        
    
        echo $form->field($model, 'presence')->textInput(['autofocus' => true, 'maxlength' => 20]);
        
        echo $form->field($model, 'presence_comment')->textarea(['maxlength' => 200]);
        
        ?>

        
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>