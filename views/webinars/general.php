<?php
use yii\helpers\Html;
use app\components\Functions;
?>
<table class="table-striped table-bordered wide font-resp" id="event-desc">
    <tbody>
    <?php if(defined('IS_PAST')): ?>
        <tr>
            <td><i><b>Итоговая явка</b></i></td>
            <td><?= $webinar['presence'] ?? 'Не указано' ?></td>
            <td><?= Html::a('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', '/events/set-presence/' . $webinar['id']);?></td>
        </tr>
        <tr>
            <td><i><b>Итоговый комментарий</b></i></td>
            <td><?= Html::encode($webinar['presence_comment']) ?? '-' ?></td>
            <td></td>
        </tr>
    <?php endif;?>
    <tr>
        <td><b>Тип</b></td>
        <td>
            Вебинар
        </td>
        <td></td>
    </tr>
    <tr>
        <td><b>Категория</b></td>
        <td><?= Html::encode($webinar['category']) ?></td>
        <td></td>
    </tr>
    <tr>
        <td><b>Дата</b></td>
        <td><?= $webinar['date_weekday']?></td>
        <td></td>
    </tr>
    <?php foreach($data as $row): ?>
        <tr>
            <td style="width: 25%"><b><?= $row['name'] ?></b></td>
            <td>
                <?php
                if($row['type_id'] == 4) {
                    //поле загрузки файла
                    if( empty($row['info']['value']) ) {
                        echo '[Файл не загружен]';
                    } else {
                        echo '<a href="/app/download?name=' . $row['info']['value']
                            . '&event_id=' . $webinar['id'] . '">[' . Html::encode($row['info']['value']) . ']</a>';
                    }

                } else if($row['type_id'] == 5) {
                    //поле даты
                    echo Functions::toSovietDate($row['info']['value']);
                } else {
                    //другое поле
                    echo Html::encode($row['info']['value'] ?? '-');
                }
                ?>
            </td>
            <td style="width: 5%" class="control-td pl-0 pr-0 text-center">
                <!--<button data-eventid= "<?= $webinar['id'] ?>" data-target="<?=$row['id']?>" class="btn btn-default btn-sm btn-edit">Правка</button>-->
                <?=
                Html::a(
                    '<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>',
                    '/webinar/' . $webinar['id'] . '/edit/' . $row['id'],
                    ['class' => 'pl-1 pr-1']
                );
                ?>
            </td>
        </tr>
        <?php if($row['has_comment']): ?>
            <tr>
                <td style="width: 25%">Комментарий: </td>
                <td><?= Html::encode($row['info']['comment']) ?? '-' ?></td>
                <td></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>

    </tbody>
</table>
