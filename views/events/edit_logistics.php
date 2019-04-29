<?php
use yii\helpers\Html;
$this->title = $title;
$this->registerJs('$(".mask-time").mask("99:99");', 
    \yii\web\View::POS_END
        );
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item"><a href="/event/<?= $event['id']?>"><?= $event['title']?></a></li>
        <li class="breadcrumb-item active"><a href="#">Править логистическую информацию</a></li>
    </ol>
</nav>
<div class="row justify-content-end">
<?=
    Html::a(
        'Удалить строку',
        '',
        ['class' => 'float-right mb-3 bg-danger p-1 text-center text-white d-block col-md-2 col-xs-12 m-3',
            'data' => [
                'method' => 'post',
                'confirm' => 'Удалить строку?',
                'params' => [
                    'remove' => 1
                ]
            ]   
        ]
            
    ); 
?>
</div>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?= $this->render('_form_logistics', compact('fields', 'means', 'model', 'event')); ?>
    </div>
</div>

