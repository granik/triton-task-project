<?php
use app\widgets\CustomBreadcrumbs;
$this->title = $title;
?>

<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['webinar/main/show', 'webinarId' => $webinar->id], $webinar->title]
    ]
]); ?>

    <!--/шапка страницы/-->
    <?= $this->render('_header', ['webinar' => $webinar]); ?>
    <!--/шапка (конец)/-->
    <!--/главное инфо/-->
    <?= $this->render('_general', ['webinar' => $webinar, 'mainInfo' => $mainInfo]); ?>
    <!--/главное инфо (конец)/-->
    <!--/спонсоры/-->
    <?= $this->render('_sponsors', ['webinar' => $webinar]); ?>
    <!--/спонсоры (конец)/-->
<div class="col-md-12 mr-md-auto ml-md-auto pull-right bg-light p-3 border-bottom">
    <i>Время последнего обновления данных события: </i><?= $webinar->updateTime; ?>
</div>