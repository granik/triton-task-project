<?php 
$this->title = $title;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item active"><a href="/event/add">Добавить событие</a></li>
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
        $params = 
        [
              'prompt' => 'Выберите'
        ];

        echo $form->field($model, 'title')->textInput(['autofocus' => true, 'maxlength' => 200]);

        echo $form->field($model, 'category_id')->dropDownList($category_items, $params); 

        echo $form->field($model, 'type_id')->dropDownList($type_items) ;
        
        echo $form->field($model, 'type_custom')->textInput(['maxlength' => 20]);

        echo $form->field($model, 'date')->widget(DatePicker::classname(), [
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

        echo $form->field($model, 'city_id')->dropDownList($city_items, $params);
         ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>