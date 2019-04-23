<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\FinanceForm;
$this->title = $title;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item"><a href="/event/<?= $event['id']?>"><?= $event['title']?></a></li>
        <li class="breadcrumb-item active"><a href="#">Добавить финансовую информацию</a></li>
    </ol>
</nav>
<div class="row justify-content-center">
    <div class="col-sm-5">
    <?php $active_form = ActiveForm::begin([
      'validateOnBlur' => false,
      'method' => 'post',
    ]); 
    echo $active_form->field($form, 'event_id')
            ->hiddenInput(['value' => $event['id'] ]);
    echo $active_form->field($form, 'type_id')
            ->dropDownList($fields, ['prompt' => 'Выберите']);
    echo $active_form->field($form, 'exist')
            ->radioList([0 => 'Нет', 1 => 'Да'], ['value' => 0]);
    echo $active_form->field($form, 'status')
            ->radioList([0 => 'Не оплачен', 1 => 'Оплачен'], ['value' => 0]);
    
    echo $active_form->field($form, 'comment')
            ->textarea();
                    
   ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    </div>
</div>

