<?php
use yii\helpers\Html;
use app\components\Functions;
use yii\helpers\Url;
?>
<table class="table-striped table-bordered wide font-resp" id="event-desc">
    <tbody>
    <?php if(defined('IS_PAST')): ?>
        <tr>
            <td><i><b>Итоговая явка</b></i></td>
            <td><?= $webinar->presence ?? 'Не указано' ?></td>
            <td><?= Html::a('Правка',
                    Url::toRoute(['event/presence/create',
                        'eventId' => $webinar->id
                    ])); ?></td>
        </tr>
        <tr>
            <td><i><b>Итоговый комментарий</b></i></td>
            <td><?= Html::encode($webinar->presence_comment) ?? '-' ?></td>
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
        <td><?= Html::encode($webinar->category['name']) ?></td>
        <td></td>
    </tr>
    <tr>
        <td><b>Дата</b></td>
        <td><?= $webinar->weekday?></td>
        <td></td>
    </tr>
    <?php foreach($mainInfo as $row): ?>
        <tr>
            <td style="width: 25%"><b><?= $row['name'] ?></b></td>
            <td>
                <?php
                if($row['type_id'] == 4) {
                    //поле загрузки файла
                    if( empty($row->field['value']) ) {
                        echo '[Файл не загружен]';
                    } else {
                        echo Html::a('[' . Html::encode($row->field['value']) . ']',
                            Url::toRoute(['app/download',
                                'name' => $row->field['value'],
                                'eventId' => $webinar->id
                            ])
                        );
                    }

                } else if($row['type_id'] == 5) {
                    //поле даты
                    echo Functions::toSovietDate($row->field['value']);
                } else {
                    //другое поле
                    echo Html::encode($row->field['value'] ?? '-');
                }
                ?>
            </td>
            <td style="width: 5%" class="control-td pl-0 pr-0 text-center">
                <?=
                Html::a(
                    'Правка',
                    Url::toRoute(['webinar/field/edit',
                        'webinarId' => $webinar->id,
                        'fieldId' => $row->id

                    ]),
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
