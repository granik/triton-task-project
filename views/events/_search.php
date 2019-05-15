<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\EventType;
use app\models\EventCategory;
use yii\helpers\ArrayHelper;
use anmaslov\autocomplete\AutoComplete;
use app\models\City;

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
    $cityList = City::find()
            ->select(['name as value', 'name as label',])
            ->where(['is_deleted' => 0])
            ->asArray()
            ->all();
    
    ?>

    <?= $form->field($model, 'type_id')
            ->dropDownList($typeItems, ['class' => 'form-control', 'prompt' => '--']) ?>

    <?= $form->field($model, 'category_id')
            ->dropDownList($catItems, ['class' => 'form-control', 'prompt' => '--']) ?>
    <?= $form->field($model, 'city');
            
            AutoComplete::widget([
            'name' => 'SearchEvent[city]',
            'data' =>  $cityList,
            'clientOptions' => [
                'minChars' => 2,
                'source' => $cityList,
                 ],
            'options' => [
                'class' => 'form-control mb-1',
                'id' => 'searchevent-city'
            ]
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a('Очистить', '?', ['class' => 'btn btn-outline-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
