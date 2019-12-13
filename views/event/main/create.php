<?php 
$this->title = $title;
$method = 'POST';
use app\widgets\CustomBreadcrumbs;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\date\DatePicker;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [[], 'Добавить']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?= $this->render('_form',
            compact('model', 'typeItems', 'categoryItems', 'cityItems', 'method')
        ); ?>
    </div>
</div>