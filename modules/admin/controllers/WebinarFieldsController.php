<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\webinar\WebinarFields;
use app\modules\admin\models\fields\WebinarFieldsForm;
use app\models\event\main\FieldType;
use app\modules\admin\controllers\SearchWebinarFields;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * WebinarFieldsController implements the CRUD actions for WebinarFields model.
 */
class WebinarFieldsController extends AppAdminController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $rules = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
        
        $parentRules = parent::behaviors();
        return array_merge_recursive($rules, $parentRules);
    }

    /**
     * Lists all WebinarFields models.
     * @return mixed
     */
    public function actionIndex()
    {
        $title = 'Поля таблиц: вебинары';
        $searchModel = new SearchWebinarFields();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('dataProvider', 'searchModel', 'title'));
    }

    /**
     * Displays a single WebinarFields model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $title = 'Просмотр поля';
        $model = new WebinarFields();
        $data = $model->find()->where(['id' => $id, 'is_deleted' => 0])->one();
        if(empty($data->id)) {
            throw new NotFoundHttpException("Страница не найдена, возможно, данные были удалены.");
        }
        return $this->render('view', [
            'model' => $data
        ]);
    }

    /**
     * Creates a new WebinarFields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $title = "Добавить поле: основное инфо";
        $model = new WebinarFieldsForm();
        $fieldTypeModel = new FieldType();
        $typeOpts = $fieldTypeModel
                ->find()
                ->asArray()
                ->all();

        $type_opts = ArrayHelper::map($typeOpts, 'id', 'name');
        

        if (Yii::$app->request->method == 'POST') {

            $post = Yii::$app->request->post('WebinarFieldsForm');
            $f = 'WebinarFieldsForm';
//            $output['_csrf'] = $_POST['_csrf'];
            $output[$f] = array();
            $output[$f]['name'] = $post['name'];
            $output[$f]['type_id'] = $post['type_id'];
            $output[$f]['has_comment'] = $post['has_comment'];
            $options = array();
            for($i=1; $i<=5; $i++) {
                if( empty($post["option{$i}"]) ) {
                    continue;
                }
                $options[(string)$i] = $post["option{$i}"];
            }
            $output[$f]['options'] = Json::encode($options);
            $output[$f]['position'] = WebinarFields::find()->max('position') + 1;
            if ( $model->load($output) && $model->save() ) {
                //всё ОК
                Yii::$app->session->setFlash('success', 'Поле <strong>' . $post['name'] . '</strong> добавлено!');                
                return $this->redirect(['index']);
            }
            
            
        }

        return $this->render('create', 
                compact('model', 'title', 'type_opts')
        );
    }

    /**
     * Updates an existing WebinarFields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $webinarFields = new WebinarFieldsForm();
        $model = $webinarFields->findOne($id);
        $fieldTypeModel = new FieldType();
        $typeOpts = $fieldTypeModel
                ->find()
                ->asArray()
                ->all();

        $type_opts = ArrayHelper::map($typeOpts, 'id', 'name');

        if (Yii::$app->request->method == 'POST') {
            $post = Yii::$app->request->post('WebinarFieldsForm');
            $f = 'WebinarFieldsForm';
//            $output['_csrf'] = $_POST['_csrf'];
            $output[$f] = array();
            $output[$f]['id'] = $post['id'];
            $output[$f]['name'] = $post['name'];
            $output[$f]['type_id'] = $post['type_id'];
            $output[$f]['has_comment'] = $post['has_comment'];
            $options = array();
            for($i=1; $i<=5; $i++) {
                if( empty($post["option{$i}"]) ) {
                    continue;
                }
                $options[(string)$i] = $post["option{$i}"];
            }
            $output[$f]['options'] = Json::encode($options);
            if( $model->load($output) && $model->save() ) {
                //всё ОК
                Yii::$app->session->setFlash('success', 'Данные сохранены!');
                return $this->redirect([
                'view',
                'id' => $model->id
                    ]);
            }
            
        }
        return $this->render('update', [
            'model' => $model,
            'options' => Json::decode($model->options, true),
            'type_opts' => $type_opts
        ]);
    }

    /**
     * Deletes an existing WebinarFields model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $row = WebinarFields::findOne($id);
        $row->name = 'removed-' . $row->name;
        $row->is_deleted = 1;
        $row->save();
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the WebinarFields model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WebinarFields the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WebinarFields::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionUp($id) {
        $model = new WebinarFields();
        $currentRow = $model->findOne($id);
        //текущая позиция текущей строки
        $curPos = $currentRow->position;
        //новая позиция текущей строки
        $newPos = $currentRow->position - 1 === 0 ? 1 : $currentRow->position - 1;

        $rowA = $model->find()->where(['position' => $newPos])->one();
        $rowB = $currentRow;
        
        $rowA->position = $curPos;
        $rowB->position = $newPos;
        $rowA->update(false);
        $rowB->update(false);
//        return $this->redirect('/admin/fields/info');
    }
    
    public function actionDown($id) {
        $model = new WebinarFields();
        $currentRow = $model->findOne($id);
        $maxPos = $model->find()->max('position');
        //текущая позиция текущей строки
        $curPos = $currentRow->position;
        //новая позиция текущей строки
        $newPos = $currentRow->position === $maxPos ? 
                  $currentRow->position : 
                  $currentRow->position + 1;

        $rowA = $model->find()->where(['position' => $newPos])->one();
        $rowB = $currentRow;
        
        $rowA->position = $curPos;
        $rowB->position = $newPos;
        $rowA->update(false);
        $rowB->update(false);
//        return $this->redirect('/admin/fields/info');
    }
    
}
