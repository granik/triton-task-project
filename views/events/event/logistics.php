<?php
use yii\helpers\Html;
use app\components\Functions;
?>
<h5 class="pt-3 pb-1 text-center"><b>Логистическая информация</b></h5>
<?=
Html::a(
    'Добавить информацию',
    '/event/' . $event['id'] . '/add-logistics',
    ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
);
?>
<table id="logistics" class="table-striped table-bordered wide font-resp">
    <?php if (!empty($logistics)): ?>
    <thead>
    <th class="d-none d-sm-table-cell"></th>
    <th>Имя</th>
    <th>Туда</th>
    <th>Проживание</th>
    <th>М.городами</th>
    <th>Домой</th>
    <th></th>
    </thead>
    <tbody>
    <?php foreach ($logistics as $item): ?>
        <tr>
            <td class="d-none d-sm-table-cell"><b><?= Html::encode($item['type']['name']); ?></b></td>
            <td><?= Html::encode($item['persons']); ?></td>
            <td><?= '<i>' . Html::encode($item['to']['name']) . '</i> ' . Functions::toShortDate($item['to_date']) . '<br>' . ($item['to_time'] ?? 'нет ') . '-' . ($item['to_arrival'] ?? ' нет') ?></td>
            <td><?= (Functions::toShortDate($item['living_from']) ?? 'нет') . '-' . (Functions::toShortDate($item['living_to']) ?? 'нет'); ?></td>
            <td><?= '<i>' . Html::encode($item['between']['name']) . '</i> ' . Functions::toShortDate($item['between_date']) . '<br>' . ($item['between_time'] ?? 'нет ') . '-' . ($item['between_arrival'] ?? ' нет') ?></td>
            <td><?= '<i>' . Html::encode($item['home']['name']) . '</i> ' . Functions::toShortDate($item['home_date']) . '<br>' . ($item['home_time'] ?? 'нет ') . '-' . ($item['home_arrival'] ?? ' нет') ?></td>
            <!--<td class=" pl-0 pr-0 text-center"><button class="btn btn-default btn-sm">Правка</button></td>-->
            <td class="control-td pl-0 pr-0 text-center">
                <!--<button class="btn btn-default btn-sm btn-edit">Удалить</button>-->
                <?=
                Html::a(
                    '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>',
                    '/event/' . $event['id'] . '/edit-logistics/' . $item['id'],
                    ['class' => 'pl-1 pr-1 text-danger text-center remove-']
                );
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php else: ?>
        <h4>Данных логистики еще нет</h4>
    <?php endif; ?>
    </tbody>
</table>