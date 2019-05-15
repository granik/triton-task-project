<?php
//echo 'Раздел в процессе разработки.';
$this->title = $title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb d-none d-sm-none d-md-flex bg-white">
        <li class="breadcrumb-item"><a href="/">Главная</a></li>
        <li class="breadcrumb-item active"><a href="/profile">Мой профиль</a></li>
    </ol>
</nav>

<div class="row">
    <?= $this->render('_menu'); ?>
    <div class="col-md-10 mr-md-auto ml-md-auto pull-right bg-light p-3" style="min-height: 500px;">
        <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        <h3 class="mt-1 ml-1"><?= $user->first_name . ' ' . $user->last_name?></h3>
        <p class="ml-1 text-secondary"><?= $user['role']['name']?></p>
<!--        <h6 class="ml-1">Мои уведомления:</h6>

        <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Новые</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Просмотренные</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <strong>Перенос!</strong> Конференция "Педиатрия для Москвичей" <b>09:00</b> &rArr; <b>10:00</b>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <strong>Перенос!</strong> Вебинар "Основы педиатрии" <b> 21.02.2019 09:00</b> &rArr; <b>23.02.2019 10:00</b>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Отмена!</strong> Семинар "Врачебное дело" <b>25.02.2019</b> <strong>Отменен</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <strong>Перенос!</strong> Конференция "Название конференции" <b>09:00</b> &rArr; <b>10:00</b>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Отмена!</strong> Семинар "Тру ля-ля" <b>25.02.2019</b> <strong>Отменен</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <strong>Перенос!</strong> Московыский семинар "Педиатрия глазами студента" <b> 21.02.2019 09:00</b> &rArr; <b>23.02.2019 10:00</b>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
        </div>-->
      </div>
    </div>
</div>
