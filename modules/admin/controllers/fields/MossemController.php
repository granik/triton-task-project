<?php

namespace app\modules\admin\controllers\fields;


use Yii;
use app\models\mossem\MossemFields;
use app\modules\admin\models\fields\MossemFieldsForm;
use app\models\event\main\FieldType;
use app\modules\admin\models\fields\SearchMossemFields;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\modules\admin\controllers\AppAdminController;

/**
 * mossemFieldsController implements the CRUD actions for mossemField model.
 */
class MossemController extends AppAdminController
{
    /**
     * @var MossemFieldsForm
     */
    protected $form;
    /**
     * @var SearchMossemFields
     */
    protected $searchModel;

    /**
     * MossemFieldsController constructor.
     * @param $id
     * @param $module
     * @param array $config
     * @param MossemFieldsForm $form
     * @param SearchMossemFields $searchModel
     */
    function __construct($id, $module, $config = [], MossemFieldsForm $form, SearchMossemFields $searchModel)
    {
        $this->form = $form;
        $this->searchModel = $searchModel;
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all mossemField models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = 'Поля таблиц: московские семинары';
        $searchModel = $this->searchModel;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('dataProvider', 'searchModel', 'title'));
    }

    /**
     * Displays a single mossemField model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $title = 'Просмотр поля';
        $model = MossemFields::findOne(['id' => $id, 'is_deleted' => 0]);
        if (empty($model->id)) {
            throw new NotFoundHttpException("Страница не найдена, возможно, данные были удалены.");
        }
        return $this->render('view', [
            'title' => $title,
            'model' => $model
        ]);
    }

    /**
     * Creates a new mossemField model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $title = "Добавить поле: мос.семинары";
        $model = $this->form;
        $typeOpts = FieldType::find()->all();

        $type_opts = ArrayHelper::map($typeOpts, 'id', 'name');

        return $this->render('create',
            compact('model', 'title', 'type_opts')
        );
    }

    /**
     * Обработчик формы добавления
     *
     * @param $id
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
        if($model->validate() && $model->save() )
        {
            Yii::$app->session->setFlash('success', 'Поле <strong>' . $model->name . '</strong> добавлено!');
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing mossemField model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionEdit($id)
    {
        $mossemFields = $this->form;
        $model = $mossemFields->findOne($id);
        if(!$model)
            throw new NotFoundHttpException('Ресурс с таким ID не найден!');
        $typeOpts = FieldType::find()->all();

        $typeOpts = ArrayHelper::map($typeOpts, 'id', 'name');

        return $this->render('edit', [
            'model' => $model,
            'options' => Json::decode($model->options, true),
            'type_opts' => $typeOpts
        ]);
    }

    /**
     * Обработчик формы редактирования
     *
     * @param $id
     * @return \yii\web\Response
     * @throws ErrorException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = MossemFieldsForm::findOne($id);
        $post = Yii::$app->request->getBodyParams();
        $formData = array_pop($post);
        $model->name = $formData['name'];
        $model->type_id = $formData['type_id'];
        $model->has_comment = $formData['has_comment'];
        $options = array();
        for ($i = 1; $i <= 5; $i++) {
            if (empty($formData["option{$i}"])) {
                continue;
            }
            $options[(string)$i] = $formData["option{$i}"];
        }
        $model->options = Json::encode($options);
        if( $model->validate() && $model->save() ) {
            Yii::$app->session->setFlash('success', 'Данные обновлены!');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        throw new ErrorException('Ошибка обработки формы');
    }

    /**
     * Deletes an existing mossemField model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $row = MossemFields::findOne($id);
        if(!$row)
            throw new NotFoundHttpException('Ресурс с таким ID не найден');
        $row->name = 'removed-' . $row->name;
        $row->is_deleted = 1;
        $row->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the mossemField model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return mossemField the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MossemFields::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUp($id)
    {
        $currentRow = MossemFields::findOne(['id' => $id, 'is_deleted' => 0]);
        //текущая позиция текущей строки
        $curPos = $currentRow->position;
        //новая позиция текущей строки
        $newPos = $currentRow->position - 1 === 0 ? 1 : $currentRow->position - 1;

        $rowA = MossemFields::findOne(['position' => $newPos, 'is_deleted' => 0]);
        while(empty($rowA)) {
            $newPos--;
            $rowA = MossemFields::findOne(['position' => $newPos, 'is_deleted' => 0]);
        }
        $rowB = $currentRow;

        $rowA->position = $curPos;
        $rowB->position = $newPos;
        $rowA->update(false);
        $rowB->update(false);
//        return $this->redirect('/admin/fields/info');
    }

    public function actionDown($id)
    {
        $currentRow = MossemFields::findOne(['id' => $id, 'is_deleted' => 0]);
        $maxPos = MossemFields::find()->where(['is_deleted' => 0])->max('position');
        //текущая позиция текущей строки
        $curPos = $currentRow->position;
        //новая позиция текущей строки
        $newPos = $currentRow->position === $maxPos ?
            $currentRow->position :
            $currentRow->position + 1;

        $rowA = MossemFields::findOne(['position' => $newPos, 'is_deleted' => 0]);
        while(empty($rowA)) {
            $newPos++;
            $rowA = MossemFields::findOne(['position' => $newPos, 'is_deleted' => 0]);
        }
        $rowB = $currentRow;

        $rowA->position = $curPos;
        $rowB->position = $newPos;
        $rowA->update(false);
        $rowB->update(false);
    }

}
