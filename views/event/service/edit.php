<?php 

use yii\helpers\Html;
use app\widgets\CustomBreadcrumbs;
use yii\helpers\Url;

$this->title = $title;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['event/main/show', 'eventId' => $event->id], $event->title],
        [[], 'Редактировать доп. услугу']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?=
            Html::a(
                'Удалить услугу',
                Url::toRoute(['event/service/delete', 'eventId' => $event->id, 'serviceId' => $model->id]),
                ['class' => 'float-right mb-3 bg-danger p-1 text-center text-white d-block col-md-3 col-xs-12',
                    'data' => [
                        'method' => 'delete',
                        'confirm' => 'Удалить доп. услугу?'
                    ]]
                ); 
        ?>
        <?= $this->render('_form', array(
            'model' => $model,
            'cityItems' => $cityItems,
            'event' => $event,
            'method' => 'PUT'
        )); ?>

    </div>
</div>