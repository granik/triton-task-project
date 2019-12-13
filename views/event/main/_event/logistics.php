<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Functions;
?>
<h5 class="pt-3 pb-1 text-center"><b>Логистическая информация</b></h5>
<?=
Html::a(
    'Добавить информацию',
    Url::toRoute(['event/logistics/create', 'eventId' => $event->id]),
    ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
);
?>
<table id="logistics" class="table-striped table-bordered wide font-resp">
    <?php if (!empty($event->logistics)): ?>
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
    <?php foreach ($event->logistics as $item): ?>
        <tr>
            <td class="d-none d-sm-table-cell"><b><?= Html::encode($item->type->name); ?></b></td>
            <td><?= Html::encode($item->persons); ?></td>
            <td><?= '<i>' . Html::encode($item->to['name']) . '</i> ' . Functions::toShortDate($item->to_date) . '<br>' . ($item->to_time ?? 'нет ') . '-' . ($item->to_arrival ?? ' нет') ?></td>
            <td><?= (Functions::toShortDate($item->living_from) ?? 'нет') . '-' . (Functions::toShortDate($item->living_to) ?? 'нет'); ?></td>
            <td><?= '<i>' . Html::encode($item->between['name']) . '</i> ' . Functions::toShortDate($item->between_date) . '<br>' . ($item->between_date ?? 'нет ') . '-' . ($item->between_arrival ?? ' нет') ?></td>
            <td><?= '<i>' . Html::encode($item->home['name']) . '</i> ' . Functions::toShortDate($item->home_date) . '<br>' . ($item->home_time ?? 'нет ') . '-' . ($item->home_arrival ?? ' нет') ?></td>
            <td class="control-td pl-0 pr-0 text-center">
                <?=
                Html::a(
                    '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>',
                    Url::toRoute(['event/logistics/delete', 'eventId' => $event->id, 'itemId' => $item->id]),
                    [
                            'data' => [
                               'method' => 'DELETE',
                                'confirm' => 'Удалить информацию?'
                            ],
                        'class' => 'pl-1 pr-1 text-danger text-center'
                    ]
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