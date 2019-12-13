<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<table class="table-striped table-bordered wide font-resp" id="event-desc">
    <tbody>
    <?php if($event->isPast): ?>
        <tr>
            <td><i><b>Итоговая явка</b></i></td>
            <td><?= $event->presence ?? 'Не указано' ?></td>
            <td class="control-td text-center"><?= Html::a('Правка',
                    Url::toRoute(['event/presence/create', 'eventId' => $event->id]));?>
            </td>
        </tr>
        <tr>
            <td><i><b>Итоговый комментарий</b></i></td>
            <td><?= Html::encode($event->presence_comment) ?? '-' ?></td>
            <td></td>
        </tr>
    <?php endif;?>
    <tr>
        <td><b>Тип</b></td>
        <td>
            <?= empty($event->type_custom) ? $event->type->name : "{$event->type->name} ({$event->type_custom})"; ?>
        </td>
        <td></td>
    </tr>
    <tr>
        <td><b>Категория</b></td>
        <td><?= Html::encode($event->category->name) ?></td>
        <td></td>
    </tr>
    <tr>
        <td><b>Город</b></td>
        <td><?= Html::encode($event->city->name) ?></td>
        <td></td>
    </tr>
    <tr>
        <td><b>Дата</b></td>
        <td><?= $event->weekday ?></td>
        <td></td>
    </tr>
    <tr>
        <td><b><i>Примечания</i></b></td>
        <td><?= Html::encode($event->notes); ?></td>
        <td></td>
    </tr>

    <?php foreach($mainInfo as $row): ?>
        <tr>
            <td class="title-td"><b><?= $row->name ?></b></td>
            <td>
                <?php
                if($row->type_id == 4) {
                    //поле загрузки файла
                    if( empty($row->field['value']) ) {
                        echo '[Файл не загружен]';
                    } else {
                        echo Html::a(Html::encode($row->field['value']),
                            Url::toRoute(['app/download', 'eventId' => $event->id, 'name' => $row->field->value]),
                            ['target' => '_blank']);
                    }

                } else if($row->type_id == 5) {
                    //поле даты
                    echo $event->date;
                } else {
                    //другое поле
                    echo Html::encode($row->field['value'] ?? '-');
                }
                ?>
            </td>
            <td class="control-td pl-0 pr-0 text-center">
                <?=
                Html::a(
                    'Правка',
                    Url::toRoute(array('event/field/edit', 'eventId' => $event->id, 'fieldId' => $row->id)),
                    ['class' => 'pl-1 pr-1']
                );
                ?>
            </td>
        </tr>
        <?php if($row->has_comment): ?>
            <tr>
                <td style="width: 25%">Комментарий: </td>
                <td><?= Html::encode($row->field['comment']) ?? '-' ?></td>
                <td></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>

    </tbody>
</table>