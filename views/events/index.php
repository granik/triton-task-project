<?php 
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\EventCategory;
use app\models\EventType;
use app\components\Functions;
$this->title = $title;
?>
<div class="row">
    <div style="width: 100%" class=" btn-group row" role="group" aria-label="Basic example">
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
                'class' => 'pl-3 pr-3 pb-2 pt-2 btn ' . $classA .  ' d-block col-sm-4'
            ]
    );
?>
<?=
    Html::a(
            'Архив событий',
            '/events/archive', 
            [
                'class' => 'pl-3 pr-3 pb-2 pt-2 btn ' . $classB . ' d-block col-sm-4'
            ]
    );
?>
        
<?=
    Html::a(
            'Добавить событие',
            '/events/add', 
            [
                'class' => 'pl-3 pr-3 pb-2 pt-2 btn btn-outline-info d-block col-sm-4'
            ]
    );
?>
        </div>
    <div class="col-md-12 mr-md-auto ml-md-auto pull-right bg-light p-3" style="min-height: 500px;">
        
        <h4 class="pt-2 pb-2"><?= $isArchive ? 'Архив событий' : 'Актуальные события'; ?></h4>
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'font-resp table-events'],
        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'onmouseover' => "this.style['background-color'] = '#cccccc'",
                'onmouseout' => "this.style['background-color'] = ''",
                'onclick' => 'location.href="' 
                    .'/event/' . $model->id . '";',
            ];
        },
        'layout'=>"{summary}\n{items}\n{pager}",
            'columns' => [
                [
                    'attribute'=>'type.name',
                    'label'=>'Событие',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'type-name', 'style' => 'width: 300px'];
                    },
                    'filter' => 
                    Html::activeDropDownList(
                            $searchModel, 
                            'type_id', 
                            ArrayHelper::map(
                                    EventType::find()->all(), 
                                    'id', 
                                    'name'
                                    ), 
                            ['class' =>'form-control d-none d-sm-block','prompt' => 'Выберите']),
                            
                ],
                [
                    'attribute'=>'category.name',
                    'label'=>'Категория',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'category', 'style' => 'width: 300px'];
                    },
                    'filter' => 
                    Html::activeDropDownList(
                            $searchModel, 
                            'category_id', 
                            ArrayHelper::map(
                                    EventCategory::find()->all(), 
                                    'id', 
                                    'name'
                                    ), 
                            ['class' =>'form-control d-none d-sm-block','prompt' => 'Выберите']),
                ],
                [
                    'attribute'=>'city.name',
                    'label'=>'Город',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'city'];
                    },
                    'filter' => 
                    Html::activeTextInput(
                            $searchModel, 
                            'city', 
                            ['class' =>'form-control d-none d-sm-block']),
                ],
                [
                    'attribute'=>'date',
                    'label'=>'Дата',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'date', 'style' => 'width: 150px'];
                    },
                    'value' => function($model) {
                        return Functions::toSovietDate($model->date);
                    },
                    'filter' => ''
                ],
            ],
 
    ]);
    ?>
    </div>
</div>