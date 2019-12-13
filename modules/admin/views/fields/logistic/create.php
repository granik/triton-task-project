<?php

use yii\helpers\Html;
use app\widgets\CustomBreadcrumbs;
/* @var $this yii\web\View */
/* @var $model app\models\LogisticFields */

$this->title = $title;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['/event/main'], 'Главная'],
        [['/admin'], 'Администрирование'],
        [['/admin/fields/logistics'], 'Поля таблиц: логистика'],
        [['/admin/fields/logistics/create'], "Создать"],
    ]
]); ?>
<div class="logistic-fields-create row">
    <?= $this->render('../../_side_menu')?>
    <div class="col-md-9">
    <?php if( Yii::$app->session->hasFlash('error') ): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('error'); ?>
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
