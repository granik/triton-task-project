<?php 
$this->title = $title;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item active"><a href="/admin">Администрирование</a></li>
        <!--<li class="breadcrumb-item active"><a href="/event/add">Добавить событие</a></li>-->
    </ol>
</nav>
<div class="row">
<?= $this->render('../_side_menu')?>
    <div class="col-md-9">
        <div class="jumbotron">
            <h2 class="display-4">Приветствие</h2>
            <p class="lead">Вы находитесь в панели администрирования.</p>
            <hr class="my-4">
            <p>В меню слева можно выбрать необходимый раздел.</p>
            <p class="lead">
              <a class="btn btn-primary" href="/" role="button">Вернуться на главную</a>
            </p>
        </div>
    </div>
</div>