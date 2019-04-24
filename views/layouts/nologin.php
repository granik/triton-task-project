<?php
use yii\helpers\Html;
use app\assets\AppAsset;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title . " - " . Html::encode(Yii::$app->params['title']) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/"><img src="/images/logo.png" width="100"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        </ul>
        <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link" href="#">
                    Войдите в систему!
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid" style="min-height: 550px">
    <?= $content ?>
</div>
<footer class="navbar navbar-light bg-light mt-5">
    <a class="navbar-brand" href="#">Компания &laquo;Тритон&raquo; &copy; <?= date('Y')?></a>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>