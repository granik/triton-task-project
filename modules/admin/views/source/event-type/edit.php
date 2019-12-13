<?php

use yii\helpers\Html;
use app\widgets\CustomBreadcrumbs;

$this->title = 'Править тип события';
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['/event/main'], 'Главная'],
        [['/admin'], 'Администрирование'],
        [['/admin/event-type'], 'Типы событий'],
        [['/admin/event-type/edit', 'id' => $model->id], 'Редактировать']
    ]
]); ?>

<div class="eveny-type-update row mt-5">
    <?= $this->render('../../_side_menu')?>
    <div class="col-md-9">
        <?php if (Yii::$app->session->hasFlash('exists')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('exists'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        <h3><?= Html::encode($this->title) ?></h3>

        <?= $this->render('_form', [
            'model' => $model,
            'method' => 'PUT'
        ]) ?>
    </div>
</div>
