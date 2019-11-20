<?php
$this->title = $title;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item active"><a href="/event/<?= $event['id'] ?>"><?= $event['title'] ?></a></li>
    </ol>
</nav>
<div class="row">
    <div class="col-md-12 mr-md-auto ml-md-auto pull-right bg-light p-3" style="min-height: 500px;">
        <!--/шапка страницы/-->
        <?= $this->render('event/header', compact('event')); ?>
        <!--//-->
        <!--/основное инфо/-->
        <?= $this->render('event/general', compact('event', 'data')); ?>
        <!--//-->
        <!--/моссеминар/-->
        <?php if (!empty($mossem_data)): ?>
            <?= $this->render('event/mossem', compact('event', 'mossem_data')); ?>
        <?php endif; ?>
        <!--//-->
        <!--/спонсоры/-->
        <?= $this->render('event/sponsors', compact('event','sponsors')); ?>
        <!--//-->
        <!--/финансы/-->
        <?= $this->render('event/finance', compact('event','finance')); ?>
        <!--//-->
        <!--/билеты/-->
        <?= $this->render('event/tickets', compact('event','ticketDataProvider')); ?>
        <!--//-->
        <!--/услуги/-->
        <?= $this->render('event/services', compact('event','services')); ?>
        <!--//-->
    </div>
</div>