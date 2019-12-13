<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\event\main\EventCategory;
use app\models\event\main\EventType;
use yii\jui\AutoComplete;
use yii\helpers\Url;


$this->title = $title;
$this->registerCssFile("/css/calendar.css?" . time());
$this->registerJsFile("/js/calendar.js?" . time());
?>
<div class="row">
    <?= $this->render('_index/headerMenu', ['isArchive' => $isArchive]); ?>
    <div class="col-lg-2 bg-light p-3">
        <!--Календарь начало-->
        <table id="calendar2">
            <thead>
            <tr>
                <td>‹
                <td id="date" colspan="5">
                <td>›
            <tr>
                <td>Пн
                <td>Вт
                <td>Ср
                <td>Чт
                <td>Пт
                <td>Сб
                <td>Вс
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
                $bgColor = !empty($model->type->color) ? $model->type->color :
                    (!empty($model->category->color) ? $model->category->color : '');
                if($model->type->name === 'Другое') {
                    $bgColor = !empty($model->category->color) ? $model->category->color :
                        (!empty($model->type->color) ? $model->type->color : '');
                }
                return [
                    'style' => 'cursor: pointer; background-color: ' . "$bgColor",
                    'onmouseover' => "this.style['background-color'] = '#fff'",
                    'onmouseout' => "this.style['background-color'] = '$bgColor'",
                    'onclick' => $model->type->name !== 'Вебинар' ? 'window.open("'
                        . '/event/' . $model->id . '");' :
                        'window.open("'
                        . '/webinar/' . $model->id . '");',
                ];
            },
            'layout' => "{summary}\n{items}\n{pager}",
            'columns' => [
                [
                    'attribute' => 'type.name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $type = empty($model->type_custom) ? $model->type->name : $model->type_custom;
                        $type = mb_strtolower($model->title) === mb_strtolower($type) ? null : $type;
                        $val = $model->title;
                        $val .= is_null($type) ? null : '<br><i class="small">' . $type . '</i>';
                        $val .= '<br><i style="font-size: .7em">Upd: ' . $model->updateTime . '</i>';
                        return $val;
                    },
                    'label' => 'Событие',
                    'contentOptions' => function ($model, $key, $index, $column) {
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
                            ['class' => 'form-control d-none d-sm-block', 'prompt' => 'Выберите']),

                ],
                [
                    'attribute' => 'category.name',
                    'label' => 'Категория',
                    'contentOptions' => function ($model, $key, $index, $column) {
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
                            ['class' => 'form-control d-none d-sm-block', 'prompt' => 'Выберите']),
                ],
                [
                    'attribute' => 'city.name',
                    'label' => 'Город',
                    'contentOptions' => function ($model, $key, $index, $column) {
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
                    'attribute' => 'date',
                    'label' => 'Дата',
                    'contentOptions' => function ($model, $key, $index, $column) {
                        return ['class' => 'date', 'style' => 'width: 150px'];
                    },
                    'value' => function ($model) use ($days) {
                        $timestamp = strtotime($model->date);
                        $date = $model->regularDate;
                        return $date . ' ' . $days[date("w", $timestamp)];
                    },
                    'filter' => Html::textInput('SearchEvent[date]', '', [
                            'class' => 'form-control d-none',
                            'id' => 'date-input'
                        ]
                    )
                ],
                [
                    'attribute' => 'notes',
                    'label' => 'Примеч.',
                    'contentOptions' => function ($model, $key, $index, $column) {
                        return ['class' => 'note'];
                    },
                    'value' => function($model) {
                        return Html::encode($model->notes);
                    }
                ],
            ],

        ]);
        ?>
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
</div>