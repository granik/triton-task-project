<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
$this->title = $title;
$this->registerJsFile('/web/js/jquery.maskedinput.min.js', [
    'position' => \yii\web\View::POS_BEGIN,
        ]
        );
$this->registerJs('$(".mask-time").mask("99:99");', 
    \yii\web\View::POS_END
        );
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item"><a href="/event/<?= $event['id']?>"><?= $event['title']?></a></li>
        <li class="breadcrumb-item active"><a href="#">Добавить логистическую информацию</a></li>
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
    echo $active_form->field($form, 'persons')
            ->textInput(['maxlength' => 50]);
    echo $active_form->field($form, 'to_means')
           ->dropDownList($means, ['prompt' => 'Выберите']);
    echo $active_form->field($form, 'to_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
//                'convertFormat' => true,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
//                    'startDate'=> date('yyyy-mm-dd', strtotime(time()))artik
                    
        ]
    ]);
    echo $active_form->field($form, 'to_time')
            ->textInput(['maxlength' => 5, 'class' => 'mask-time']);
    echo $active_form->field($form, 'living_from')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
//                'convertFormat' => true,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
//                    'startDate'=> date('yyyy-mm-dd', strtotime(time()))artik
                    
        ]
    ]);
    
    echo $active_form->field($form, 'living_to')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
//                'convertFormat' => true,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
//                    'startDate'=> date('yyyy-mm-dd', strtotime(time()))artik
                    
        ]
    ]);
    
    echo $active_form->field($form, 'between_means')
           ->dropDownList($means, ['prompt' => 'Выберите']);
    echo $active_form->field($form, 'between_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
//                'convertFormat' => true,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
//                    'startDate'=> date('yyyy-mm-dd', strtotime(time()))artik
                    
        ]
    ]);
    
    echo $active_form->field($form, 'between_time')
            ->textInput(['maxlength' => 5, 'class' => 'mask-time']);
    
    echo $active_form->field($form, 'home_means')
           ->dropDownList($means, ['prompt' => 'Выберите']);
    echo $active_form->field($form, 'home_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Дата'],
                'removeButton' => false,
//                'convertFormat' => true,
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'bsVersion' => '4',
//                    'startDate'=> date('yyyy-mm-dd', strtotime(time()))artik
                    
        ]
    ]);
    echo $active_form->field($form, 'home_time')
            ->textInput(['maxlength' => 5, 'class' => 'mask-time']);
    ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end();?>
    </div>
</div>

