<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\user\CreateUserForm;
use app\modules\admin\models\user\UpdateUserForm;
use app\models\User;
use app\modules\admin\models\user\SearchUser;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\common\UserRole;
use yii\helpers\Url;

/**
 * Контроллер, отвечающий за пользователей сайта
 *
 * @author Granik
 */
class UserController extends AppAdminController
{
    /**
     * Инстанс модели поиска
     * @var SearchUser
     */
    protected $search_model;
    /**
     * Инстанс модели формы создания
     * @var CreateUserForm
     */
    protected $create_form;

    /**
     * UserController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param SearchUser $search_model
     * @param CreateUserForm $create_form
     */
    public function __construct($id, $module, $config = [], SearchUser $search_model, CreateUserForm $create_form)
    {
        $this->search_model = $search_model;
        $this->create_form = $create_form;
        parent::__construct($id, $module, $config);
    }
    /**
     * Список пользователей
     *
     * @return string
     */
    public function actionList()
    {
        $title = 'Пользователи';
        $dataProvider = $this->search_model->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $this->search_model,
            'dataProvider' => $dataProvider,
            'title' => $title,
        ]);
    }

    /**
     * Страница с формой создания пользователя
     *
     * @return string
     */
    public function actionCreate()
    {
        return $this->render('create', [
                'title' => 'Новый пользователь',
                'model' => $this->create_form
            ]
        );
    }

    /**
     * Обработчик формы создания пользователя
     *
     * @return \yii\web\Response
     * @throws ErrorException
     */
    public function actionStore()
    {
        $model = $this->create_form;
        if ($model->load(Yii::$app->request->post())) {
            $model->signup();
            return $this->redirect(['list']);
        }
        throw new ErrorException('Ошибка обработки формы');

    }

    /**
     * Страница просмотра пользователя
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $title = "Пользователи";
        $model = User::findOne($id);
        if (empty($model->id)) {
            throw new NotFoundHttpException("Страница не найдена");
        }
        return $this->render('view', compact('model', 'title'));
    }

    /**
     * Страница с формой редактирования пользователя
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionEdit($id)
    {
        $title = 'Правка пользователя';
        $model = UpdateUserForm::findOne($id);
        $roles = UserRole::findAll();
        $roles = ArrayHelper::map($roles, 'id', 'name');
        if (empty($model->id)) {
            throw new NotFoundHttpException("Страница не найдена");
        }
        return $this->render('edit', compact('title', 'model', 'roles'));
    }

    /**
     * Обработчик формы редактирования пользователей
     *
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\base\ErrorException
     */
    public function actionUpdate($id)
    {
        $model = UpdateUserForm::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->updateUser($id)) {
            Yii::$app->session->setFlash('success', 'Данные обновлены!');
            return $this->redirect(['view', 'id' => $id]);
        }
        throw new ErrorException('Ошибка обработки формы!');
    }

    /**
     * Экшн удаления пользователя
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $user = User::findOne($id);
        if (empty($user->id)) {
            throw new NotFoundHttpException("Нет пользователя с таким ID");
        }
        $user->is_deleted = 1;
        $user->update();
        Yii::$app->session->setFlash('success', 'Пользователь успешно деактивирован');
        return $this->redirect(['list']);
    }

    /**
     * Экшн восстановления пользователя
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionRestore($id)
    {
        $user = User::findOne($id);
        if (empty($user->id)) {
            throw new NotFoundHttpException("Нет пользователя с таким ID");
        }
        $user->is_deleted = 0;
        $user->update();
        Yii::$app->session->setFlash('success', 'Пользователь успешно активирован');
        return $this->redirect(['list']);
    }
}
