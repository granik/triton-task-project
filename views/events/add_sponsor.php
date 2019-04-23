<?php
$this->title = $title;
//use app\models\AddSponsorForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item"><a href="/event/<?= $event['id']?>"><?= $event['title']?></a></li>
        <li class="breadcrumb-item active"><a href="#">Добавить спонсора</a></li>
    </ol>
</nav>
<div class="row justify-content-center">
    <div class="col-sm-5">
    <?php $active_form = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'post',
    ]); 
    echo $active_form->field($form, 'event_id')
            ->hiddenInput(['value' => $event['id']]);
    echo $active_form->field($form, 'name')
            ->textInput(['autofocus' => true, 'maxlength' => 50]);
    echo $active_form->field($form, 'type_id')
            ->dropDownList($type_opts, ['prompt' => 'Выберите']); 
    ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    </div>
</div>

