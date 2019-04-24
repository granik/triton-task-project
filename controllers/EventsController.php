<?php
/**
 * Created by PhpStorm.
 * User: Granik
 * Date: 13.02.2019
 * Time: 14:54
 */

namespace app\controllers;

use Yii;
use yii\helpers\{Json, ArrayHelper};
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
//models
use app\models\{
    Event,
    EventCategory,
    City,
    EventType,
    InfoFields,
    EventInfo,
    Sponsor,
    SponsorType,
    LogisticInfo,
    LogisticMeans,
    LogisticFields,
    FinanceInfo,
    FinanceFields,
    SearchEvent
};

//forms
use app\models\{
    AddEventForm,
    EditFieldForm,
    ChangeDataForm,
    LogisticsForm,
    FinanceForm,
    AddSponsorForm
};




class EventsController extends AppController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
   
    public function actionIndex() {
        $isArchive = false;
        $title = $isArchive ? 'Архив событий' : "Актуальные события";
        $Category = new EventCategory();
        $categories = $Category->getCategories();
        $searchModel = new SearchEvent();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        

        return $this->render('index', 
                compact('title', 
                        'categories', 
                        'searchModel', 
                        'dataProvider',
                        'isArchive')
                );
    }
    
    public function actionArchive() {
        $isArchive = true;
        $title = $isArchive ? 'Архив событий' : "Актуальные события";
        $Category = new EventCategory();
        $categories = $Category->getCategories();
        $searchModel = new SearchEvent(['is_archive' => true]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        

        return $this->render('index', 
                compact('title', 
                        'categories', 
                        'searchModel', 
                        'dataProvider',
                        'isArchive')
                );
    }

    public function actionEvent($id) {
        $title = "Страница события";
        $where = ['event_id' => $id, 'is_deleted' => 0];
        $eventModel = new Event();
        $event = $eventModel->getEventAsArray($id);
        $event['date'] = $this->toRussianDate($event['date']);
        
        $model = new InfoFields();
        $data = $model::find()->with([ 
        'info' => function (\yii\db\ActiveQuery $query) use ($id) {
            
        $query->andWhere('event_id = '. $id);
        },])
            ->leftJoin('event_info', 'field_id')
            ->where(['info_fields.is_deleted' => 0])
                //чтобы если поле было удалено из админки, 
                //в событиях заполненная инфа осталась
            ->orWhere("event_info.value <> '' AND event_info.field_id = info_fields.id")
            ->orderBy(['position' => SORT_ASC])
            ->asArray()
            ->all();
        
        $sponsor = new Sponsor();
        $sponsors = $sponsor
                ->find()
                ->with('type')
                ->where($where)
                ->asArray()
                ->all();
        
        $logisticInfo = new LogisticInfo();
        $logistics = $logisticInfo
                ->find()
                ->with(['to', 'between', 'home', 'type'])
                ->where($where)
                ->asArray()
                ->all();
        
        $financeInfo = new FinanceInfo();
        $finance = $financeInfo->find()
                ->with('fields')
                ->where($where)
                ->asArray()
                ->all();
        return $this->render('event', 
            compact(
                'title',
                'id',
                'data',
                'event',
                'sponsors',
                'logistics',
                'finance'
            )
        );
    }
    
    public function actionAdd() {
        $title = "Добавить событие";
        $model = new AddEventForm();
        
        if ($model->load(Yii::$app->request->post())) {

            if($model->save()){
                return $this->goBack();
            }
        }
        
        $Cities = new City();
        $city = $Cities->getCitiesAsArray();
        $Category = new EventCategory();
        $categories = $Category->getCategories();
        $types = EventType::getEventTypes();
        
        return $this->render('add',
                compact('title',
                        'model', 
                        'city',
                        'categories',
                        'types'
                        )
                );
    }
    
    public function actionEditField($event_id, $field_id) {
        $title = "Редактирование";
        $edit_form = new EditFieldForm();
        $where = compact('event_id', 'field_id');
        
        
        /*обработчик для поля с файлом: начало*/
        
        /*  обработчик для поля с файлом: конец */
        $InfoFields = new InfoFields();
        //получаем данные указанного поля
        $field = $InfoFields
                    ->find()
                    ->with(['type', 'info' => function(\yii\db\ActiveQuery $query) use ($event_id) {
                        $query->andWhere('event_id = ' . $event_id);
                    }])
                    ->where(['id' => $field_id])
                    ->one();
                    
        if ($edit_form->load( Yii::$app->request->post() )) {
            
            if($field->type->name == 'file') {
                //пришел файл
                $file = UploadedFile::getInstance($edit_form, 'file_single');
                if(empty($file)) {
                    //пришло пустое файловое поле
                    $edit_form->updateOnlyComment($event_id, $field_id);
                    return $this->redirect(['event', 'id' => $event_id]);
                }
                
                if(!$edit_form->uploadFile($file, $event_id, $field_id)){
                    throw new \yii\base\ErrorException("Невозможно загрузить файл!");
                }
                
                return $this->redirect(['event', 'id' => $event_id]);
                
            }
            
            if(  null == EventInfo::find()->where($where)->one() ) {
                //если поле ранее не было заполнено
                if(!$edit_form->createNew($event_id, $field_id)) {
                    throw new \yii\base\ErrorException("Невозможно вставить данные!");
                }
             
            } else if( !$edit_form->updateData($event_id, $field_id) ) {
                //обновляем существующее
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }
            
            
            return $this->redirect(['event', 'id' => $event_id]);
        }
        
        /*обработчик формы: конец*/
        
        $model = EditFieldForm::findOne($where);
        $eventModel = new Event();
        //получаем данные о событии (для breadcrumbs и title) 
        $event = $eventModel->getEventAsArray($event_id);
        //отдача шаблона
        return $this->render('edit_field', 
                compact(
                'title',
                'model',
                'field',
                'event'
                )
            );
    }
    
    public function actionChangeData($event_id) {
        $title = "Изменить событие";
        $change_form = new ChangeDataForm();
        /* begin form handler */
        if ($change_form->load(Yii::$app->request->post())) {
            if(!$change_form->updateData()) {
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }
            return $this->redirect(['event', 'id' => $event_id]);
        }
        /* end form handler */
        $model = new ChangeDataForm();
        $event = $model->findOne($event_id);
        $CityModel = new City();
        $city = $CityModel->getCitiesAsArray();
        $Category = new EventCategory();
        $categories = $Category->getCategories();
        $types = EventType::getEventTypes();
        //отдача шаблона
        return $this->render('change_data',
                compact(
                        'event',
                        'title',
                        'city',
                        'categories',
                        'types'
                        )
                );
    }
    
    public function actionAddSponsor($event_id) {
        $title = "Добавить спонсора";
        $eventModel = new Event();
        $event = $eventModel->find()
                ->where(['id' => $event_id])
                ->asArray()
                ->one();
        $sponsorTypeModel = new SponsorType();
        $types = $sponsorTypeModel
                ->find()
                ->asArray()
                ->all();
        $type_opts = ArrayHelper::map($types, 'id', 'name');
        $form = new AddSponsorForm();
        
        /*begin form handler*/
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->save();
            return $this->redirect(['event', 'id' => $event_id] );
        }
        /*end form handler*/
        
        return $this->render('add_sponsor', 
                compact(
                        'title',
                        'form',
                        'type_opts',
                        'event'
                        )
                );
    }
    
    public function actionAddLogistics($event_id) {
        $title = "Добавить логистическую информацию";
        
        
        $eventModel = new Event();
        $event = $eventModel->find()
                ->where(['id' => $event_id])
                ->asArray()
                ->one();
        
        $form = new LogisticsForm();
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->save();
            return $this->redirect(['event', 'id' => $event_id]);
        }
        
        $fieldsModel = new LogisticFields();
        $fields = $fieldsModel
                ->find()
                ->asArray()
                ->all();
        $meansModel = new LogisticMeans();
        $means = $meansModel
                ->find()
                ->asArray()
                ->all();
        
        $means = ArrayHelper::map($means, 'id', 'name');
        $fields = ArrayHelper::map($fields, 'id', 'name');
        
        return $this->render('add_logistics', 
                compact(
                        'title',
                        'form',
                        'fields',
                        'event',
                        'means'
                        )
                );
    }
    
    public function actionEditLogistics($event_id, $item_id) {
        $title = "Править логистическую информацию";
        
        
        $eventModel = new Event();
        $event = $eventModel->find()
                ->where(['id' => $event_id])
                ->asArray()
                ->one();
        
        $form = new LogisticsForm();
                
        $where = ['id' => $item_id];
        
        $model = $form
                ->find()
                ->where($where)
                ->one();
        
        /* delete */
        if( Yii::$app->request->post('remove') ) {
            $currentRow = LogisticInfo::findOne($item_id);
            $currentRow->is_deleted = 1;
            $currentRow->update();
            return $this->redirect(['event', 'id' => $event_id]);
        }
        /* end delete */
        
        $form = new LogisticsForm();
        /* begin handler form */
        if ($form->load(Yii::$app->request->post())) {
//            $postData = Yii::$app->request->post('LogisticsForm');
            if( !$form->updateData($item_id) ) {
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }
            return $this->redirect(['event', 'id' => $event_id]);
        }
        /* end handler form */
        
        $fieldsModel = new LogisticFields();
        $fields = $fieldsModel
                ->find()
                ->asArray()
                ->all();
        $meansModel = new LogisticMeans();
        $means = $meansModel
                ->find()
                ->asArray()
                ->all();
        
        $means = ArrayHelper::map($means, 'id', 'name');
        $fields = ArrayHelper::map($fields, 'id', 'name');
        
        return $this->render('edit_logistics', 
                compact(
                        'title',
                        'form',
                        'fields',
                        'event',
                        'means',
                        'model'
                        )
                );
    }
    
    public function actionAddFinance($event_id) {
        $title = "Добавить финансовую информацию";
        $eventModel = new Event();
        $event = $eventModel->find()
                ->where(['id' => $event_id])
                ->asArray()
                ->one();
        
        $form = new FinanceForm();
        
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->save();
            return $this->redirect(['event', 'id' => $event_id]);
        }
        
        $fieldsModel = new FinanceFields();
        $fields = $fieldsModel
                ->find()
                ->asArray()
                ->all();
        
        $fields = ArrayHelper::map($fields, 'id', 'name');
        
        
        return $this->render('add_finance', 
                compact(
                        'title',
                        'form',
                        'fields',
                        'event'
                        )
                );
        
    }
    
    public function actionEditFinance($event_id, $item_id) {
        $title = "Править финансовую информацию";
        $eventModel = new Event();
        $event = $eventModel->find()
                ->where(['id' => $event_id])
                ->asArray()
                ->one();
        
        $form = new FinanceForm();
        $where = ['id' => $item_id, 'is_deleted' => 0];
        if ($form->load(Yii::$app->request->post())) {
            
            if( !$form->updateData($item_id) ) {
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }
            return $this->redirect(['event', 'id' => $event_id]);
        }
        
        $fieldsModel = new FinanceFields();
        $fields = $fieldsModel
                ->find()
                ->asArray()
                ->all();
        
        $fields = ArrayHelper::map($fields, 'id', 'name');
        $model = new FinanceForm();
        $model = $model
                ->find()
                ->where($where)
                ->one();
        /* delete handler begin */
        if( Yii::$app->request->post('remove') ) {
            $currentRow = FinanceInfo::findOne(['id' => $item_id]);
            $currentRow->is_deleted = 1;
            $currentRow->update();
            return $this->redirect('/event/'. $event['id']);
        }
        /* delete handler end*/
        
        return $this->render('edit_finance', 
                compact(
                        'title',
                        'form',
                        'fields',
                        'event',
                        'model'
                        )
                );
        
    }
    
    public function actionRemoveSponsor($id, $event_id) {
        $sponsor = Sponsor::find()->where(compact('id', 'event_id'))->one();
        $sponsor->is_deleted = 1;
        $sponsor->update();
        return $this->redirect('/event/' . $event_id);
   
    }
    
    public function actionCancel($event_id) {
        
        $eventModel = new Event();
        $event = $eventModel->findOne($event_id);
        
        $event->is_cancel = 1;
        $event->update();
        
        return $this->redirect('/event/' . $event_id);
        
        
    }
    
    public function actionAbortCancel($event_id) {
        $eventModel = new Event();
        $event = $eventModel->findOne($event_id);
        
        $event->is_cancel = 0;
        $event->update();
        
        return $this->redirect('/event/' . $event_id);
        
        
    }
    
    public function actionUnlinkFile($event_id, $field_id) {
        $InfoFields = new InfoFields();
        $field = $InfoFields
                    ->find()
                    ->with(['type', 'info' => function(\yii\db\ActiveQuery $query) use ($event_id) {
                        $query->andWhere('event_id = ' . $event_id);
                    }])
                    ->where(['id' => $field_id])
                    ->one();
        $fileField = EventInfo::findOne(compact('event_id', 'field_id'));
        
        if($field->type->name != 'file') {
            throw new \yii\base\ErrorException("Данное поле не имеет тип 'file'!");
        }
        
        
        @unlink(Yii::$app->params['pathUploads'] . 'event_files/' . $event_id . '/' . $fileField->value);
        $fileField->value = null;
        $fileField->save();
        
        return $this->redirect([
            'edit-field', 
            'event_id' => $event_id,
            'field_id' => $field_id
                ]);
        
    }
  
    
}