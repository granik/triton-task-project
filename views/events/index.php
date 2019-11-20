<?php 
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\event\main\EventCategory;
use app\models\event\main\EventType;
use app\components\Functions;
use app\models\common\City;
use yii\jui\AutoComplete;
$this->title = $title;
$this->registerCssFile("/css/calendar.css?" . time());
$this->registerJsFile("/js/calendar.js?" . time());
?>
<div class="row">
    <div class="row btn-group" style="width: 100%; margin: 0;" role="group" aria-label="Basic example">
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
    
    $cityList = City::find()
            ->select(['name as value', 'name as label',])
            ->where(['is_deleted' => 0])
            ->asArray()
            ->all();

?>
        </div>
    <div class="col-lg-2 bg-light p-3">
        <!--Календарь начало--> 
        <table id="calendar2">
            <thead>
              <tr><td>‹<td id="date" colspan="5"><td>›
              <tr><td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс
            <tbody>
        </table>
        <!--Календарь конец-->
    </div>
    <div class="col-lg-10 col-xs-12 mr-md-auto ml-md-auto pull-right bg-light p-3" style="min-height: 500px;">
        
        <h4 class="pt-2 pb-2"><?= $isArchive ? 'Архив событий' : 'Актуальные события'; ?></h4>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'font-resp table-events grid-view'],
        'rowOptions' => function ($model, $key, $index, $grid) {
           $bgColor =   !empty($model->type->color) ? $model->type->color :
                       (!empty($model->category->color) ? $model->category->color : '');
            return [
                'style' => 'cursor: pointer; background-color: ' . "$bgColor",
                'onmouseover' => "this.style['background-color'] = '#fff'",
                'onmouseout' => "this.style['background-color'] = '$bgColor'",
                'onclick' => $model->type->name !== 'Вебинар' ? 'window.open("'
                    .'/event/' . $model->id . '");' :
                'window.open("'
                    .'/webinar/' . $model->id . '");',
            ];
        },
        'layout'=>"{summary}\n{items}\n{pager}",
            'columns' => [
                [
                    'attribute'=>'type.name',
                    'format' => 'raw',
                    'value' => function($model) {
                        $val = null;
                        
                        if(!empty($model->type_custom)) {
                            $val = $model->type->name . ' (' . $model->type_custom . ')';
                        } else {
                            $val = $model->type->name;
                        }
                        
                        if(!empty($model->updated_on)) {
                            $val .= "<br>"
                                . '<i style="font-size: .7em">Upd: ' 
                                . date('d.m.Y H:i', $model->updated_on + 3*3600) . '</i>';
                        } else {
                            $val .= "<br>"
                                . '<i style="font-size: .7em">- ';
                        }
                        
                        return $val;
                    },
                    'label'=>'Событие',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'type-name', 'style' => 'width: 300px; line-height: 1rem'];
                    },
                    'filter' => 
                    Html::activeDropDownList(
                            $searchModel, 
                            'type_id', 
                            ArrayHelper::map(
                                    EventType::findAll(['is_deleted' => 0]), 
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
                                    EventCategory::findAll(['is_deleted' => 0]), 
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
                    'filter' => AutoComplete::widget([
                        'attribute' => 'city',
                        'name' => 'SearchEvent[city]',
                        'model' => $cityList,
                        'clientOptions' => [
                            'source' => $cityList,
                            'minChars' => 2,
                        ],
                        'options' => [
                            'class' => 'form-control d-none d-md-block'
                        ]
                    ])
                ],
                [
                    'attribute'=>'date',
                    'label'=>'Дата',
                    'contentOptions' =>function ($model, $key, $index, $column){
                        return ['class' => 'date', 'style' => 'width: 150px'];
                    },
                    'value' => function($model) use ($days) {
                        $timestamp = strtotime($model->date);
                        $date = Functions::toSovietDate($model->date);
                        return $date . ' ' . $days[date("w", $timestamp)];
                    },
                    'filter' => Html::textInput('SearchEvent[date]','', [
                                'class' => 'form-control d-none',
                                'id'    => 'date-input'
                            ]
                        )
                ],
            ],
 
    ]);
    ?>
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
</div>