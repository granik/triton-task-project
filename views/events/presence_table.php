<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Functions;
/* @var $this yii\web\View */
/* @var $searchModel app\models\admin\SearchLogisticFields */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
?>
<div class='row button-group' role="group" aria-label="Basic example">
<?php
    if(!$isArchive) {
        $classA = 'btn-outline-primary';
        $classB = 'btn-outline-secondary';
    } else {
        $classB = 'btn-outline-primary';
        $classA = 'btn-outline-secondary';
    }
?>
<?=
    Html::a(
            'Актуальные события',
            '/', 
            [
                'class' => 'pl-3 pr-3 pb-2 pt-2 btn ' . $classA .  ' d-block col-sm-3'
            ]
    );
?>
<?=
    Html::a(
            'Архив событий',
            '/events/archive', 
            [
                'class' => 'pl-3 pr-3 pb-2 pt-2 btn ' . $classB . ' d-block col-sm-3'
            ]
    );
?>
        
<?=
    Html::a(
            'Итоговая явка',
            '/events/presence-table', 
            [
                'class' => 'pl-3 pr-3 pb-2 pt-2 btn btn-outline-info d-block col-sm-3'
            ]
    );
?>
        
<?=
    Html::a(
            'Добавить событие',
            '/events/add', 
            [
                'class' => 'pl-3 pr-3 pb-2 pt-2 btn btn-outline-info d-block col-sm-3'
            ]
    );

?>
    </div>
<div class="row">
    
    <div class="col-md-12">
        <h3 class="mt-4 mb-3"><?= Html::encode($this->title) ?></h3>
        <?php Pjax::begin(); ?>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label'=>'Событие',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a(
                                $model->city . ' ' . Functions::toSovietDate($model->date),'/event/' . $model->id
                                );
                    },
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['style' => 'width: 300px'];
                    },
                ],
                [
                    'label'=>'Явка',
                    'value' => function($model) {
                        return $model->presence;
                    },
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['style' => 'width: 200px'];
                    },
                ],
                [
                    'label'=>'Комментарий',
                    'value' => function($model) {
                        return $model->presence_comment;
                    },
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'name'];
                    },
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>

