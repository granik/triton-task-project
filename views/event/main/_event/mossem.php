<?php
use yii\helpers\Html;
use app\components\Functions;
use yii\helpers\Url;
?>
<h5 class="mt-3 mb-2">Данные о московском семинаре:</h5>
<table class="table-striped table-bordered wide font-resp" id="mossem-desc">
    <tbody>
    <?php foreach ($mossemData as $row): ?>
        <tr>
            <td style="width: 25%"><b><?= $row->name ?></b></td>
            <td>
                <?php
                if ($row['type_id'] == 4) {
                    //поле загрузки файла
                    echo empty($row->field['value']) ? '[Файл не загружен]' :
                        Html::a(
                            $row->field->value,
                            Url::toRoute(['app/download', 'event_id' => $event->id, 'name' => $row->field['name']])
                        );

                } else if ($row['type_id'] == 5) {
                    //поле даты
                    echo Functions::toSovietDate($row->field['value']);
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
                    Url::toRoute(['event/field/edit-mossem', 'fieldId' => $row->id, 'mossemId' => $event->id]),
                    ['class' => 'pl-1 pr-1']
                );
                ?>
            </td>
        </tr>
        <?php if ($row->has_comment): ?>
            <tr>
                <td style="width: 25%">Комментарий:</td>
                <td><?= Html::encode($row->field['comment']) ?? '-' ?></td>
                <td></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>

    </tbody>
</table>