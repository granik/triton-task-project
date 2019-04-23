<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\EventType;
use app\models\City;
use app\models\EventCategory;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\admin\SearchLogisticFields */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="logistic-fields-search d-block d-sm-none">

    <?php $form = ActiveForm::begin([
        'action' => [''],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    
    <?php 
    $types = EventType::find()->where(['is_deleted' => 0])->all();
    $typeItems = ArrayHelper::map($types, 'id', 'name');
    $categories = EventCategory::find()->where(['is_deleted' => 0])->all();
    $catItems = ArrayHelper::map($categories, 'id', 'name');
    ?>

    <?= $form->field($model, 'type_id')
            ->dropDownList($typeItems, ['class' => 'form-control', 'prompt' => '--']) ?>

    <?= $form->field($model, 'category_id')
            ->dropDownList($catItems, ['class' => 'form-control', 'prompt' => '--']) ?>

    <?= $form->field($model, 'city')->textInput(['class' => 'form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a('Очистить', '?', ['class' => 'btn btn-outline-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
