<?php 
$this->title = $title;
use yii\helpers\Html;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item"><a href="/events/<?= $event->id ?>"><?= $event->title ?></a></li>
        <li class="breadcrumb-item active"><a href="#">Править доп. услугу</a></li>
    </ol>
</nav>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?=
            Html::a(
                'Удалить услугу',
                '/event/' . $event['id'] . '/delete-service/' . $model->id,
                ['class' => 'float-right mb-3 bg-danger p-1 text-center text-white d-block col-md-3 col-xs-12',
                    'data' => [
                        'method' => 'post',
                        'confirm' => 'Удалить доп. услугу?'
                    ]]
                ); 
        ?>
        <?= $this->render('_form_service', compact('model', 'city_items', 'event')); ?>

    </div>
</div>