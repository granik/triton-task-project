<?php

use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<div class="password-reset">
    <p>Здравствуйте, <b><?= Html::encode($user->first_name) ?></p>,</p>
    <p>Для восстановления пароля перейдите по ссылке: </p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>

