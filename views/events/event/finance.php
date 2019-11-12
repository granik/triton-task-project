<?php
use yii\helpers\Html;
?>
<h5 class="pt-3 pb-1 text-center"><b>Финансовое сопровождение</b></h5>
<?=
Html::a(
    'Добавить информацию',
    '/event/' . $event['id'] . '/add-finance',
    ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
);
?>
<table id="finances" class="table-striped table-bordered wide font-resp">
    <?php if (!empty($finance)): ?>
        <tr>
            <td><b>Тип</b></td>
            <td><b>Наличие</b></td>
            <td><b>Статус</b></td>
            <td><b>Примечания</b></td>
            <td></td>
        </tr>
        <?php foreach ($finance as $item): ?>
            <tr>
                <td><?= Html::encode($item['fields']['name']) ?></td>
                <td><?= Html::encode($item['exist']) ? 'Да' : 'Нет' ?></td>
                <td><?= Html::encode($item['status']) ? 'Оплачен' : 'Не оплачен' ?></td>
                <td><?= Html::encode($item['comment']) ?></td>
                <td class="control-td pl-0 pr-0 text-center">
                    <?=
                    Html::a(
                        '<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>',
                        '/event/' . $event['id'] . '/edit-finance/' . $item['id'],
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