<?php

use app\widgets\CustomBreadcrumbs;

$this->title = $title;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'Главная'],
        [[], 'Профиль']
    ]
]); ?>
<div class="row">
    <?= $this->render('_menu'); ?>
    <div class="col-md-10 mr-md-auto ml-md-auto pull-right bg-light p-3" style="min-height: 500px;">
        <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        <h3 class="mt-1 ml-1"><?= $user->first_name . ' ' . $user->last_name ?></h3>
        <p class="ml-1 text-secondary"><?= $user['role']['name']?></p>
      </div>
    </div>
</div>
