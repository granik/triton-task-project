<?php
use yii\helpers\Html;
$this->title = $title;
$this->registerJsFile('/web/js/jquery.maskedinput.min.js', [
    'position' => \yii\web\View::POS_BEGIN,
        ]
        );
$this->registerJs('$(".mask-time").mask("99:99");', 
    \yii\web\View::POS_END
        );
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item"><a href="/event/<?= $event['id']?>"><?= $event['title']?></a></li>
        <li class="breadcrumb-item active"><a href="#">Добавить логистическую информацию</a></li>
    </ol>
</nav>
<div class="row justify-content-center">
    <div class="col-sm-5">
    <?= $this->render('_form_logistics', [
        'model' => $form,
        'means' => $means,
        'fields' => $fields
    ]); ?>
    </div>
</div>

