<?php

use yii\helpers\Html;
use app\widgets\CustomBreadcrumbs;

$this->title = $title;


?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['/event/main'], 'Главная'],
        [['/admin'], 'Администрирование'],
        [['/admin/sponsor-type'], 'Типы спонсоров'],
        [['/admin/sponsor-type/create'], 'Создать']
    ]
]); ?>
<div class="sponsor-type-create row">
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
        <?= $this->render('_form', [
            'model' => $model,
            'method' => 'POST'
        ]) ?>
    </div>
</div>
