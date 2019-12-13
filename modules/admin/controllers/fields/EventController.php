<?php

namespace app\modules\admin\controllers\fields;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\event\main\EventMainFields;
use app\models\event\main\FieldType;
use app\modules\admin\models\fields\EventMainFieldsForm;
use app\modules\admin\models\fields\SearchEventMainFields;
use app\modules\admin\controllers\AppAdminController;

/**
 * InfoFieldsController implements the CRUD actions for InfoFields model.
 */
class EventController extends AppAdminController
{
    /**
     * @var EventMainFieldsForm
     */
    protected $form;
    /**
     * @var SearchEventMainFields
     */
    protected $searchModel;

    /**
     * InfoFieldsController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param SearchEventMainFields $searchModel
     * @param EventMainFieldsForm $form
     */
    function __construct($id, $module, $config = [], SearchEventMainFields $searchModel, EventMainFieldsForm $form)
    {
        $this->searchModel = $searchModel;
        $this->form = $form;
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all InfoFields models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = "Поля: основное инфо";
        $dataProvider = $this->searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $this->searchModel,
            'dataProvider' => $dataProvider,
            'title' => $title,
        ]);
    }

    /**
     * Displays a single InfoFields model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = EventMainFields::findOne(['id' => $id, 'is_deleted' => 0]);
        if (empty($model->id)) {
            throw new NotFoundHttpException("Страница не найдена, возможно, данные были удалены.");
        }
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new InfoFields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $title = "Добавить поле: основное инфо";
        $model = $this->form;
        $type_opts = ArrayHelper::map(FieldType::find()->all(), 'id', 'name');

        return $this->render('create',
            compact('model', 'title', 'type_opts')
        );
    }

    /**
     * Обработчик формы добавления записи
     *
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStore()
    {
        $model = $this->form;

        $post = Yii::$app->request->getBodyParams();
        $formData = array_pop($post);
        $model->name = $formData['name'];
        $model->type_id = $formData['type_id'];
        $model->has_comment = $formData['has_comment'];
        $model->position = 1 + $model->find()->max('position');
        $options = array();
        for ($i = 1; $i <= 5; $i++) {
            if (empty($formData["option{$i}"])) {
                continue;
            }
            $options[(string)$i] = $formData["option{$i}"];
        }
        $model->options = Json::encode($options);
        if ($model->validate() && $model->save()) {
            Yii::$app->session->setFlash('success', 'Поле <strong>' . $model->name . '</strong> добавлено!');
            return $this->redirect(['index']);
        }

    }

    /**
     * Updates an existing InfoFields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {
        $model = EventMainFieldsForm::findOne($id);
        $typeOpts = ArrayHelper::map(FieldType::find()->all(), 'id', 'name');

        return $this->render('edit', [
            'model' => $model,
            'options' => Json::decode($model->options, true),
            'type_opts' => $typeOpts
        ]);
    }

    /**
     * Обработчик формы редактирования
     *
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->form->findOne($id);
        $post = Yii::$app->request->getBodyParams();
        $formData = array_pop($post);
        $model->name = $formData['name'];
        $model->type_id = $formData['type_id'];
        $model->has_comment = $formData['has_comment'];
        $model->position = 1 + $model->find()->max('position');
        $options = array();
        for ($i = 1; $i <= 5; $i++) {
            if (empty($formData["option{$i}"])) {
                continue;
            }
            $options[(string)$i] = $formData["option{$i}"];
        }
        $model->options = Json::encode($options);
        if ($model->validate() && $model->save()) {
            Yii::$app->session->setFlash('success', 'Данные обновлены!');
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing InfoFields model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $row = EventMainFields::findOne($id);
        if (empty($row))
            throw new NotFoundHttpException('Ресурс с таким ID не найден!');
        $row->name = 'removed-' . $row->name;
        $row->is_deleted = 1;
        $row->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the InfoFields model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EventMainFields the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EventMainFields::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUp($id)
    {
        $currentRow = EventMainFields::findOne(['id' => $id, 'is_deleted' => 0]);
        //текущая позиция текущей строки
        $curPos = $currentRow->position;
        //новая позиция текущей строки
        $newPos = $currentRow->position - 1 === 0 ? 1 : $currentRow->position - 1;
        $rowA = EventMainFields::findOne(['position' => $newPos, 'is_deleted' => 0]);
        while (empty($rowA)) {
            $newPos--;
            $rowA = EventMainFields::findOne(['position' => $newPos, 'is_deleted' => 0]);
        }

        $rowB = $currentRow;

        $rowA->position = $curPos;
        $rowB->position = $newPos;
        $rowA->update(false);
        $rowB->update(false);
    }

    public function actionDown($id)
    {
        $currentRow = EventMainFields::findOne(['id' => $id, 'is_deleted' => 0]);
        $maxPos = EventMainFields::find()->where(['is_deleted' => 0])->max('position');
        //текущая позиция текущей строки
        $curPos = $currentRow->position;
        //новая позиция текущей строки
        $newPos = $currentRow->position === $maxPos ?
            $currentRow->position :
            $currentRow->position + 1;

        $rowA = EventMainFields::findOne(['position' => $newPos, 'is_deleted' => 0]);
        while (empty($rowA)) {
            $newPos++;
            $rowA = EventMainFields::findOne(['position' => $newPos, 'is_deleted' => 0]);
        }
        $rowB = $currentRow;

        $rowA->position = $curPos;
        $rowB->position = $newPos;
        $rowA->update(false);
        $rowB->update(false);
    }
}
