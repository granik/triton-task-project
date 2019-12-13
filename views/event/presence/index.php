<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = $title;
?>
<?=$this->render('../main/_index/headerMenu', ['isArchive' => false]); ?>
<div class="row">
    
    <div class="col-md-12">
        <h3 class="mt-4 mb-3"><?= Html::encode($this->title) ?></h3>
        <?php Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label'=>'Событие',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a(
                                $model->city . ' ' . $model->regularDate,'/event/' . $model->id
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

