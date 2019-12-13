<?php
$this->title = $title;
use app\widgets\CustomBreadcrumbs;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [[], $event->title]
    ]
]); ?>
<div class="row">
    <div class="col-md-12 mr-md-auto ml-md-auto pull-right bg-light p-3" style="min-height: 500px;">
        <!--/шапка страницы/-->
        <?= $this->render('_event/header', compact('event')); ?>
        <!--//-->
        <!--/основное инфо/-->
        <?= $this->render('_event/general', compact('event', 'mainInfo')); ?>
        <!--//-->
        <!--/моссеминар/-->
        <?php if (!empty($mossemData)): ?>
            <?= $this->render('_event/mossem', compact('event', 'mossemData')); ?>
        <?php endif; ?>
        <!--//-->
        <!--/спонсоры/-->
        <?= $this->render('_event/sponsors', compact('event')); ?>
        <!--//-->
        <!--/логистика/-->
        <?= $this->render('_event/logistics', compact('event')); ?>
        <!--//-->
        <!--/финансы/-->
        <?= $this->render('_event/finance', compact('event')); ?>
        <!--//-->
        <!--/билеты/-->
        <?= $this->render('_event/tickets', compact('event','ticketDataProvider')); ?>
        <!--//-->
        <!--/услуги/-->
        <?= $this->render('_event/services', compact('event')); ?>
        <!--//-->
    </div>
    <div class="col-md-12 mr-md-auto ml-md-auto pull-right bg-light p-3 border-bottom">
        <i>Время последнего обновления данных события: </i><?= $event->updateTime; ?>
    </div>
</div>