<?php
use yii\helpers\Html;
use app\components\Functions;
?>
<h5 class="mt-3 mb-2">Данные о московском семинаре:</h5>
<table class="table-striped table-bordered wide font-resp" id="mossem-desc">
    <tbody>
    <?php foreach ($mossem_data as $row): ?>
        <tr>
            <td style="width: 25%"><b><?= $row['name'] ?></b></td>
            <td>
                <?php
                if ($row['type_id'] == 4) {
                    //поле загрузки файла
                    if (empty($row['info']['value'])) {
                        echo '[Файл не загружен]';
                    } else {
                        echo '<a href="/app/download?name=' . $row['info']['value']
                            . '&event_id=' . $event['id'] . '">[' . Html::encode($row['info']['value']) . ']</a>';
                    }

                } else if ($row['type_id'] == 5) {
                    //поле даты
                    echo Functions::toSovietDate($row['info']['value']);
                } else {
                    //другое поле
                    echo Html::encode($row['info']['value'] ?? '-');
                }
                ?>
            </td>
            <td class="control-td pl-0 pr-0 text-center">
                <!--<button data-eventid= "<?= $event['id'] ?>" data-target="<?= $row['id'] ?>" class="btn btn-default btn-sm btn-edit">Правка</button>-->
                <?=
                Html::a(
                    '<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>',
                    '/mossem/' . $event['id'] . '/edit/' . $row['id'],
                    ['class' => 'pl-1 pr-1']
                );
                ?>
            </td>
        </tr>
        <?php if ($row['has_comment']): ?>
            <tr>
                <td style="width: 25%">Комментарий:</td>
                <td><?= Html::encode($row['info']['comment']) ?? '-' ?></td>
                <td></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>

    </tbody>
</table>