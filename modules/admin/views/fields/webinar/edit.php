<?php

use yii\helpers\Html;
use app\widgets\CustomBreadcrumbs;


$this->title = 'Правка поля';
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['/event/main'], 'Главная'],
        [['/admin'], 'Администрирование'],
        [['/admin/fields/webinar'], 'Поля таблиц: вебинары'],
        [['/admin/fields/webinar/edit', 'id' => $model->id], 'Создать']
    ]
]); ?>
<div class="info-fields-update row">
    <?= $this->render('../../_side_menu')?>
    <div class="col-md-9">
        
        <?php if( Yii::$app->session->hasFlash('exists') ): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= Yii::$app->session->getFlash('exists'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <h3><?= Html::encode($this->title) ?></h3>
        <?= $this->render('_form',
            [
                'model' => $model,
                'type_opts' => $type_opts,
                'options' => $options,
                'method' => 'PUT'
            ]) ?>
    </div>
</div>