<?php

namespace app\controllers;

use app\models\common\ProfileEditForm;
use Yii;
use app\models\common\ChangePasswordForm;
use yii\base\ErrorException;

/**
 * Контроллер, отвечающий за профиль пользователя
 *
 * @author Granik
 */
class ProfileController extends AppController
{
    /**
     * Экземпляр пользователя
     *
     * @var \yii\web\IdentityInterface|null
     */
    protected $user;

    /**
     * ProfileController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @throws \Throwable
     */
    public function __construct($id, $module, $config = array())
    {
        $this->user = Yii::$app->user->getIdentity();
        parent::__construct($id, $module, $config);
    }

    /**
     * Страница профиля пользователя
     *
     * @return string
     */
    public function actionIndex()
    {
        $title = "Профиль";
        $user = $this->user;
        return $this->render('profile', compact('title', 'user'));
    }

    /**
     * Страница редактирования данных пользователя
     *
     * @return string|\yii\web\Response
     */
    public function actionEdit()
    {
        $title = "Редактировать профиль";
        $model = ProfileEditForm::findOne($this->user->id);
        return $this->render('edit', compact('title', 'model'));
    }

    /**
     * Обработчик формы редактирования данных пользователя
     *
     * @return \yii\web\Response
     * @throws ErrorException
     */
    public function actionUpdate()
    {
        $model = ProfileEditForm::findOne($this->user->id);
        if ($model->load(Yii::$app->request->post()) && $model->updateProfile()) {
            Yii::$app->session->setFlash('success', 'Данные профиля сохранены!');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Неправильный текущий пароль!');
            return $this->redirect(['edit']);
        }
    }

    /**
     * Страница изменения пароля пользователя
     *
     * @return string|\yii\web\Response
     */
    public function actionChangePassword()
    {
        $title = "Смена пароля";
        $model = ChangePasswordForm::findOne($this->user->id);


        return $this->render('changePassword', compact('title', 'model'));

    }

    /**
     * Обработчик формы изменения пароля
     *
     * @return \yii\web\Response
     * @throws ErrorException
     */
    public function actionUpdatePassword()
    {
        $model = ChangePasswordForm::findOne($this->user->id);
        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->user->logout();
            Yii::$app->session->setFlash('success', 'Пароль профиля изменен!');
            return $this->redirect(['site/login']);
        }
    }
}
