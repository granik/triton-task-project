<?php
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

Здравствуйте,  <?= $user->first_name ?>,
Для восстановления пароля перейдите по ссылке:

<?= $resetLink ?>
