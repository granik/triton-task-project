<?php 
$this->title = $title;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\widgets\CustomBreadcrumbs;
?>
<?= CustomBreadcrumbs::widget([
    'content' => [
        [['event/main'], 'События'],
        [['event/main/show', 'eventId' => $event->id], $event->title],
        [[], 'Добавить итоговую явку']
    ]
]); ?>
<div class="row justify-content-center">
    <div class="col-sm-5">
        <?php $form = ActiveForm::begin([
                        'validateOnBlur' => false,
                        'method' => 'POST',
                      ]);
        
    
        echo $form->field($model, 'presence')->textInput(['autofocus' => true, 'maxlength' => 20]);
        
        echo $form->field($model, 'presence_comment')->textarea(['maxlength' => 200]);
        
        ?>

        
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary d-block ml-auto mr-auto', 'name' => 'send-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>