<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<h5 class="pt-3 pb-1 text-center"><b>Финансовое сопровождение</b></h5>
<?=
Html::a(
    'Добавить информацию',
    Url::toRoute(['event/finance/create', 'eventId' => $event->id]),
    ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
);
?>
<table id="finances" class="table-striped table-bordered wide font-resp">
    <?php if (!empty($event->finances)): ?>
        <tr>
            <td><b>Тип</b></td>
            <td><b>Наличие</b></td>
            <td><b>Статус</b></td>
            <td><b>Примечания</b></td>
            <td></td>
        </tr>
        <?php foreach ($event->finances as $finance): ?>
            <tr>
                <td><?= Html::encode($finance->type->name) ?></td>
                <td><?= Html::encode($finance->exist) ? 'Да' : 'Нет' ?></td>
                <td><?= Html::encode($finance->status) ? 'Оплачен' : 'Не оплачен' ?></td>
                <td><?= Html::encode($finance->comment) ?></td>
                <td class="control-td pl-0 pr-0 text-center">
                    <?=
                    Html::a(
                        'Правка',
                        Url::toRoute(['event/finance/edit', 'eventId' => $event->id, 'itemId' => $finance->id]),
                        ['class' => 'pl-1 pr-1 text-center']
                    );
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
    <h4>Данных о финансах еще нет</h4>
    <?php endif; ?>
</table>