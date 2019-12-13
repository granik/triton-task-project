<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Functions;
?>
<h5 class="pt-3 pb-1 text-center"><b>Дополн. услуги</b></h5>
<?=
Html::a(
    'Добавить услугу',
    Url::toRoute(['event/service/create', 'eventId' => $event->id]),
    ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
);
?>
<table id="finances" class="table-striped table-bordered wide font-resp">
    <?php if (!empty($event->services)): ?>
        <tr>
            <td class="d-none d-md-block">
                <strong>Название</strong>
            </td>
            <td>
                <strong>Заказчик</strong>
            </td>
            <td>
                <strong>Исполнит.</strong>
            </td>
            <td>
                <strong>Город отпр.</strong>
            </td>
            <td>
                <strong>Дата</strong>
            </td>
            <td>
                <strong>Кол-во чел.</strong>
            </td>
            <td>
                <strong>Оплач.</strong>
            </td>
            <td>
            </td>
        </tr>
        <?php foreach ($event->services as $service): ?>
            <tr>
                <td class="d-none d-md-block">
                    <strong><?= Html::encode($service->title); ?></strong>
                </td>
                <td>
                    <?= Html::encode($service->customer); ?>
                </td>
                <td>
                    <?= Html::encode($service->producer); ?>
                </td>
                <td>
                    <?php
                    if (!empty($service->city_name)) {
                        echo Html::encode($service->city_name);
                    } else {
                        echo Html::encode($service->city->name);
                    }
                    ?>
                </td>
                <td>
                    <?= Html::encode(Functions::toShortDate($service->date)); ?>
                </td>
                <td>
                    <?= Html::encode($service->people_amount); ?>
                </td>
                <td>
                    <?= $service->status == 0 ? 'Нет' : 'Да'; ?>
                </td>
                <td class=" pl-0 pr-0 text-center">
                    <?=
                    Html::a(
                        'Правка',
                        Url::toRoute(['event/service/edit', 'eventId' => $event->id, 'serviceId' => $service->id]),
                        ['class' => 'pl-1 pr-1 text-center text-primary']
                    );
                    ?>
                </td>

            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <h4>Доп. услуг еще нет</h4>
    <?php endif; ?>
</table>