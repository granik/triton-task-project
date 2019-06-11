<?php 
$this->title = $title;
use yii\helpers\Html;
use app\components\Functions;
use yii\helpers\Json;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/events">События</a></li>
        <li class="breadcrumb-item active"><a href="#"><?= $webinar['title']?></a></li>
    </ol>
</nav>
<div class="row">
    <div class="col-md-12 mr-md-auto ml-md-auto pull-right bg-light p-3" style="min-height: 500px;">
        <?php if ($webinar['is_cancel']): ?>
        <div class="alert alert-info" role="alert">
            Событие отменено!
        </div>
        <?php elseif(strtotime($webinar['date']) < time() ):
            define('IS_PAST', true);
            ?>
        <div class="alert alert-primary" role="alert">
            Событие прошло <b><?=$webinar['date']?></b>
        </div>
        <?php endif; ?>
        <h6 class="pl-2 pr-2 mb-4 float-left"><?= Html::encode($webinar['title']) ?></h6>
        <?=
            Html::a(
                'Редактировать основную информацию',
                '/event/' . $webinar['id'] . '/edit',
                ['class' => 'p-1 bg-primary text-white text-center float-right mb-2 col-md-4 col-xs-12']
                ); 
        ?>
        <table class="table-striped table-bordered wide font-resp" id="event-desc">
            <tbody>
                <?php if(defined('IS_PAST')): ?>
                <tr>
                    <td><i><b>Итоговая явка</b></i></td>
                    <td><?= $event['presence'] ?? 'Не указано' ?></td>
                    <td><?= Html::a('Правка', '/events/set-presence/' . $event['id']);?></td>
                </tr>
                <tr>
                    <td><i><b>Итоговый комментарий</b></i></td>
                    <td><?= Html::encode($event['presence_comment']) ?? '-' ?></td>
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
                    <td style="width: 8%" class=" pl-0 pr-0 text-center">
                        <!--<button data-eventid= "<?= $webinar['id'] ?>" data-target="<?=$row['id']?>" class="btn btn-default btn-sm btn-edit">Правка</button>-->
                        <?=
                            Html::a(
                                'Правка',
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
        <h5 class="pt-3 pl-2 pb-2 text-center"><b>Информация о спонсорах</b></h5>
        <?=
            Html::a(
                'Добавить спонсора',
                '/webinar/' . $webinar['id'] . '/add-sponsor',
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
                            '/webinar/' . $webinar['id'] . '/remove-sponsor/' . $sponsor['id'],
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
        <?php endif;?>
    
        </table>
    </div>
</div>