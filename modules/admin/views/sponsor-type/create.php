<?php

use yii\helpers\Html;
use app\components\CustomBreadcrumbs;
/* @var $this yii\web\View */
/* @var $model app\models\SponsorType */

$this->title = $title;

$breadcrumbs = array();
$breadcrumbs[] = ['/', 'Главная'];
$breadcrumbs[] = ['/admin', 'Администрирование'];
$breadcrumbs[] = ['/admin/sponsor-type', 'Типы спонсоров'];
$breadcrumbs[] = ['/admin/sponsor-type/create', 'Новый'];
echo CustomBreadcrumbs::widget(['content' => $breadcrumbs])

?>
<div class="sponsor-type-create row">
    <?= $this->render('../_side_menu')?>
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
        ]) ?>
    </div>
</div>
