<?php
use yii\helpers\Html;
?>
<h5 class="pt-3 pl-2 pb-2 text-center"><b>Информация о спонсорах</b></h5>
<?=
Html::a(
    'Добавить спонсора',
    '/event/' . $event['id'] . '/add-sponsor',
    ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
);
?>
<?php if (!empty($sponsors)): ?>
<table id="sponsors" class="table-striped table-bordered wide font-resp">
    <tr>
        <td><b>Имя спонсора</b></td>
        <td><b>Тип спонсора</b></td>
        <td></td>
    </tr>

    <?php foreach ($sponsors as $sponsor): ?>
        <tr>
            <td><?= Html::encode($sponsor['name']) ?></td>
            <td><?= Html::encode($sponsor['type']['name']) ?></td>
            <td class="control-td pl-0 pr-0 text-center">
                <!--<button class="btn btn-default btn-sm btn-edit">Удалить</button>-->
                <?=
                Html::a(
                    '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>',
                    '/event/' . $event['id'] . '/remove-sponsor/' . $sponsor['id'],
                    ['class' => 'pl-1 pr-1 text-center text-danger',
                        'data' => [
                            'method' => 'post',
                            'confirm' => 'Удалить спосора?'
                        ]
                    ]
                );
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php else: ?>
        <h4>Данных о спонсорах еще нет</h4>
    <?php endif; ?>

</table>