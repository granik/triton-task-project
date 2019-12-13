<?php

namespace app\modules\admin\controllers\fields;

use Yii;
use app\models\webinar\WebinarField;
use app\modules\admin\models\fields\WebinarFieldForm;
use app\models\event\main\FieldType;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\modules\admin\models\fields\SearchWebinarField;
use app\modules\admin\controllers\AppAdminController;

/**
 * WebinarFieldsController implements the CRUD actions for WebinarField model.
 */
class WebinarController extends AppAdminController
{
    /**
     * @var WebinarFieldForm
     */
    protected $form;
    /**
     * @var SearchWebinarField
     */
    protected $searchModel;

    /**
     * WebinarFieldsController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param WebinarFieldForm $form
     * @param SearchWebinarField $searchModel
     */
    function __construct($id, $module, $config = [], WebinarFieldForm $form, SearchWebinarField $searchModel)
    {
        $this->form = $form;
        $this->searchModel = $searchModel;
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all WebinarField models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = 'Поля таблиц: вебинары';
        $searchModel = $this->searchModel;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('dataProvider', 'searchModel', 'title'));
    }

    /**
     * Displays a single WebinarField model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $title = 'Просмотр поля';
        $model = WebinarField::findOne(['id' => $id, 'is_deleted' => 0]);
        if (empty($model)) {
            throw new NotFoundHttpException("Страница не найдена, возможно, данные были удалены.");
        }
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new WebinarField model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $title = "Добавить поле: основное инфо";
        $model = $this->form;
        $typeOpts = WebinarField::find()->all();

        $typeOpts = ArrayHelper::map($typeOpts, 'id', 'name');

        return $this->render('create',
            ['model' => $model, 'title' => $title, 'type_opts' => $typeOpts]
        );
    }

    /**
     * Обработчик формы создания
     *
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStore()
    {
        $model = $this->form;
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
        if($model->validate() && $model->save() )
        {
            Yii::$app->session->setFlash('success', 'Поле <strong>' . $model->name . '</strong> добавлено!');
            return $this->redirect(['index']);
        }

    }

    /**
     * Updates an existing WebinarField model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {
        $model = WebinarFieldForm::findOne($id);
        $typeOpts = FieldType::find()->all();

        $typeOpts = ArrayHelper::map($typeOpts, 'id', 'name');
        return $this->render('edit', [
            'model' => $model,
            'options' => Json::decode($model->options, true),
            'type_opts' => $typeOpts
        ]);
    }

    /**
     * Обработчик формы редаткирования
     *
     * @param $id
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
        if($model->validate() && $model->save() )
        {
            Yii::$app->session->setFlash('success', 'Данные обновлены!');
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing WebinarField model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $row = WebinarField::findOne($id);
        if(empty($row))
            throw new NotFoundHttpException('Ресурс с таким ID не найден!');
        $row->name = 'removed-' . $row->name;
        $row->is_deleted = 1;
        $row->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WebinarField model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WebinarField the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WebinarField::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUp($id)
    {
        $currentRow = WebinarField::findOne(['id' => $id, 'is_deleted' => 0]);
        if(empty($currentRow))
            throw new NotFoundHttpException('Ресурс с таким ID не найден!');
        //текущая позиция текущей строки
        $curPos = $currentRow->position;
        //новая позиция текущей строки
        $newPos = $currentRow->position - 1 === 0 ? 1 : $currentRow->position - 1;

        $rowA = WebinarField::findOne(['position' => $newPos, 'is_deleted' => 0]);
        while(empty($rowA)) {
            $newPos--;
            $rowA = WebinarField::findOne(['position' => $newPos, 'is_deleted' => 0]);
        }

        $rowB = $currentRow;

        $rowA->position = $curPos;
        $rowB->position = $newPos;
        $rowA->update(false);
        $rowB->update(false);
    }

    public function actionDown($id)
    {
        $currentRow = WebinarField::findOne(['id' => $id, 'is_deleted' => 0]);
        if(empty($currentRow))
            throw new NotFoundHttpException('Ресурс с таким ID не найден!');
        $maxPos = WebinarField::find()->where(['is_deleted' => 0])->max('position');
        //текущая позиция текущей строки
        $curPos = $currentRow->position;
        //новая позиция текущей строки
        $newPos = $currentRow->position === $maxPos ?
            $currentRow->position :
            $currentRow->position + 1;

        $rowA = WebinarField::findOne(['position' => $newPos, 'is_deleted' => 0]);
        while(empty($rowA)) {
            $newPos++;
            $rowA = WebinarField::findOne(['position' => $newPos, 'is_deleted' => 0]);
        }
        $rowB = $currentRow;

        $rowA->position = $curPos;
        $rowB->position = $newPos;
        $rowA->update(false);
        $rowB->update(false);
    }

}
