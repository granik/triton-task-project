<?php 
$this->title = $title;
use yii\helpers\Html;
use app\components\Functions;
use yii\helpers\Json;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item active"><a href="#"><?= $event['title']?></a></li>
    </ol>
</nav>
<div class="row">
    <div class="col-md-12 mr-md-auto ml-md-auto pull-right bg-light p-3" style="min-height: 500px;">
        <?php if ($event['is_cancel']): ?>
        <div class="alert alert-info" role="alert">
            Событие отменено!
        </div>
        <?php endif; ?>
        <h6 class="pl-2 pr-2 mb-4 float-left"><?= Html::encode($event['title']) ?></h6>
        <?=
            Html::a(
                'Редактировать основную информацию',
                '/event/' . $event['id'] . '/edit',
                ['class' => 'p-1 bg-primary text-white text-center float-right mb-2 col-md-4 col-xs-12']
                ); 
        ?>
        <table class="table-striped table-bordered wide font-resp" id="event-desc">
            <tbody>
                <tr>
                    <td><b>Категория</b></td>
                    <td><?= Html::encode($event['category']) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Город</b></td>
                    <td><?= Html::encode($event['city']) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>Дата</b></td>
                    <td><?= $event['date']?></td>
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
                                . '&event_id=' . $event['id'] . '">[' . Html::encode($row['info']['value']) . ']</a>';
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
                    <td style="width: 8%" class=" pl-0 pr-0 text-center">
                        <!--<button data-eventid= "<?= $event['id'] ?>" data-target="<?=$row['id']?>" class="btn btn-default btn-sm btn-edit">Правка</button>-->
                        <?=
                            Html::a(
                                'Правка',
                                '/event/' . $event['id'] . '/edit/' . $row['id'],
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
        <h5 class="pt-3 pl-2 pb-2 text-center"><b>Информация о спонсорах</b></h5>
        <?=
            Html::a(
                'Добавить спонсора',
                '/event/' . $event['id'] . '/add-sponsor',
                ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
                ); 
        ?>
        <?php if( !empty($sponsors) ): ?>
        <table id="sponsors" class="table-striped table-bordered wide font-resp">
            <tr>
                <td><b>Имя спонсора</b></td>
                <td><b>Тип спонсора</b></td>
                <td></td>
            </tr>
            
            <?php foreach($sponsors as $sponsor): ?>
            <tr>
                <td><?= Html::encode($sponsor['name']) ?></td>
                <td><?= Html::encode($sponsor['type']['name']) ?></td>
                <td style="width: 8%" class=" pl-0 pr-0 text-center">
                        <!--<button class="btn btn-default btn-sm btn-edit">Удалить</button>-->
                    <?=
                        Html::a(
                            'Удалить',
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
            <h4>Данных о спонсорах нет</h4>
        <?php endif;?>
    
        </table>
        <h5 class="pt-3 pb-1 text-center"><b>Логистическая информация</b></h5>
        <?=
            Html::a(
                'Добавить информацию',
                '/event/' . $event['id'] . '/add-logistics',
                ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
                ); 
        ?>
        <table id="logistics" class="table-striped table-bordered wide font-resp">
            <?php if( !empty($logistics) ): ?>
            <thead>
                <th class="d-none d-sm-table-cell"></th>
                <th>Имя</th>
                <th>Туда, выезд</th>
                <th>Проживание</th>
                <th>М.городами, выезд</th>
                <th>Домой, выезд</th>
                <th></th>
            </thead>
            <tbody>
            <?php foreach($logistics as $item): ?>
                <tr>
                    <td class="d-none d-sm-table-cell"><b><?= Html::encode($item['type']['name']); ?></b></td>
                    <td><?= Html::encode($item['persons']); ?></td>
                    <td><?= Html::encode($item['to']['name']) . ' ' . Functions::withoutSec($item['to_time']) . ' ' . Functions::toShortDate($item['to_date']) ?></td>
                    <td><?= (Functions::toShortDate($item['living_from']) ?? 'н/у') . '-' . (Functions::toShortDate($item['living_to']) ?? 'н/у'); ?></td>
                    <td><?= Html::encode($item['between']['name']) . ' ' . Functions::withoutSec($item['between_time']) . ' ' . Functions::toShortDate($item['between_date']) ?></td>
                    <td><?= Html::encode($item['home']['name']) . ' ' . Functions::withoutSec($item['home_time']) . ' ' . Functions::toShortDate($item['home_date']) ?></td>
                    <!--<td class=" pl-0 pr-0 text-center"><button class="btn btn-default btn-sm">Правка</button></td>-->
                    <td style="width: 8%" class=" pl-0 pr-0 text-center">
                            <!--<button class="btn btn-default btn-sm btn-edit">Удалить</button>-->
                        <?=
                            Html::a(
                                'Правка',
                                '/event/' . $event['id']  . '/edit-logistics/' . $item['id'],
                                ['class' => 'pl-1 pr-1 text-center remove-']
                                ); 
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <h4>Данных логистики нет</h4>
            <?php endif;?>
            </tbody>
        </table>
        <h5 class="pt-3 pb-1 text-center"><b>Финансовое сопровождение</b></h5>
        <?=
            Html::a(
                'Добавить информацию',
                '/event/' . $event['id'] . '/add-finance',
                ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
                ); 
        ?>
        <table id="finances" class="table-striped table-bordered wide font-resp">
            <?php if( !empty($finance) ): ?>
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
                <td><?= Html::encode($item['status']) ? 'Оплачен' : 'Не оплачен'?></td>
                <td><?= Html::encode($item['comment']) ?></td>
                <td class=" pl-0 pr-0 text-center">
                        <?=
                                Html::a(
                                    'Правка',
                                    '/event/' . $event['id']  . '/edit-finance/' . $item['id'],
                                    ['class' => 'pl-1 pr-1 text-center']
                                    ); 
                            ?>
                </td>
            </tr>
            <?php endforeach;?>
            <?php else: ?>
            <h4>Данных о финансах нет</h4>
            <?php endif; ?>
        </table>
        <h5 class="pt-3 pb-1 text-center"><b>Электронные билеты</b></h5>
        <?=
            Html::a(
                'Добавить билет',
                '/event/' . $event['id'] . '/add-ticket',
                ['class' => 'float-right mb-3 bg-primary p-1 text-center text-white d-block col-md-2 col-xs-12']
                ); 
        ?>
        <table id="finances" class="table-striped table-bordered wide font-resp">
            <?php if( !empty($tickets) ): ?>
            <tr>
                <td>
                    <strong>Имя файла</strong>
                </td>
                <td>
                </td>
            </tr>
            <?php foreach ($tickets as $ticket): 
            $url = "/app/download?name=" . $ticket['filename'] . "&event_id=" . $event['id'];
            ?>
            <tr>
                <td style="word-wrap: break-word;"><?= Html::a($ticket['filename'], $url, [])?></td>
                <td class=" pl-0 pr-0 text-center">
                        <?=
                                Html::a(
                                    'Удалить',
                                    '/event/' . $event['id']  . '/delete-ticket/' . $ticket['id'],
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
            <?php endforeach;?>
            <?php else: ?>
            <h4>Билетов нет</h4>
            <?php endif; ?>
        </table>
    </div>
</div>