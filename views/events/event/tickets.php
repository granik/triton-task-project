<?php
use yii\helpers\Html;
?>
<h5 class="pt-3 pb-1 text-center"><b>Электронные билеты</b></h5>
<?=
Html::a(
    'Добавить билет',
    '/event/' . $event['id'] . '/add-ticket',
    ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12',]
);
?>
<table id="finances" class="table-striped table-bordered wide font-resp">
    <?php if (!empty($tickets)): ?>
        <tr>
            <td style="width: 90%">
                <strong>Имя файла</strong>
            </td>
            <td>
            </td>
        </tr>
        <?php foreach ($tickets as $ticket):
            $url = "/app/download?name=" . $ticket['filename'] . "&event_id=" . $event['id'];
            ?>
            <tr>
                <td style="word-wrap: break-word;"><?= Html::a($ticket['filename'], $url, ['target' => '_blank']) ?></td>
                <td class="control-td pl-0 pr-0 text-center">
                    <?=
                    Html::a(
                        '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>',
                        '/event/' . $event['id'] . '/delete-ticket/' . $ticket['id'],
                        ['class' => 'pl-1 pr-1 text-center text-danger',
                            'data' => [
                                'method' => 'post',
                                'confirm' => 'Удалить билет?'
                            ]
                        ]
                    );
                    ?>
                </td>

            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <h4>Билетов еще нет</h4>
    <?php endif; ?>
</table>