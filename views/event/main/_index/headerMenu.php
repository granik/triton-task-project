<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

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
        Url::toRoute(['event/main/archive']),
        [
            'class' => 'pl-3 pr-3 pb-2 pt-2 btn ' . $classB . ' d-block col-sm-3'
        ]
    );
    ?>

    <?=
    Html::a(
        'Итоговая явка',
        Url::toRoute(['event/presence/index']),
        [
            'class' => 'pl-3 pr-3 pb-2 pt-2 btn btn-outline-info d-block col-sm-3'
        ]
    );
    ?>

    <?=
    Html::a(
        'Добавить событие',
        Url::toRoute(['event/main/create']),
        [
            'class' => 'pl-3 pr-3 pb-2 pt-2 btn btn-outline-info d-block col-sm-3'
        ]
    );

    ?>
</div>