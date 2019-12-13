<?php

use yii\helpers\Html;
use app\widgets\CustomBreadcrumbs;

/* @var $this yii\web\View */
/* @var $model app\models\InfoFields */

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Info Fields', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['/event/main'], 'Главная'],
        [['/admin'], 'Администрирование'],
        [['/admin/fields/main'], 'Поля таблиц: основное инфо'],
        [['/admin/fields/main/create'], 'Создать']
    ]
]); ?>
<div class="row"> 
<?= $this->render('../../_side_menu')?>
    <div class="col-md-9">
        <div class="info-fields-create">
            <?php if( Yii::$app->session->hasFlash('exists') ): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= Yii::$app->session->getFlash('exists'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>
            <h3 class="mt-2 pb-2"><?= Html::encode($this->title) ?></h3>
            <?= $this->render('_form', [
                'model' => $model,
                'type_opts' => $type_opts,
                'method' => 'POST'
            ]) ?>

        </div>
    </div>
</div>
