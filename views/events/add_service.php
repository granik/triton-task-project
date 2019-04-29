<?php 
$this->title = $title;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item active"><a href="/event/add-service">Добавить доп. услугу</a></li>
    </ol>
</nav>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?= $this->render('_form_service', compact('model', 'city_items', 'event')); ?>

    </div>
</div>