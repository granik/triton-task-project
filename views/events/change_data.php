<?php 
$this->title = $title;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\Url;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item active"><a href="/event/add">Редактировать событие</a></li>
    </ol>
</nav>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?php $form = ActiveForm::begin([
          'validateOnBlur' => false,
          'method' => 'post',
    ]);
        
        $category_items = ArrayHelper::map($categories,'id','name');
        $type_items = ArrayHelper::map($types, 'id', 'name');
        $city_items = ArrayHelper::map($city, 'id', 'name');
        
        echo $form->field($event, 'id')->hiddenInput([ 'value' => $event['id'] ]);

        echo $form->field($event, 'title')->textInput(['autofocus' => false, 'maxlength' => 200]);

        echo $form->field($event, 'category_id')->dropDownList($category_items); 

        echo $form->field($event, 'type_id')->dropDownList($type_items) ;
        
        echo $form->field($event, 'type_custom')->textInput(['maxlength' => 20]) ;

        echo $form->field($event, 'date')->widget(DatePicker::classname(), [
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

        echo $form->field($event, 'city_id')->dropDownList($city_items);
         ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
