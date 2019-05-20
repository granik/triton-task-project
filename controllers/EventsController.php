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
    SearchEvent,
    EventTicket,
    EventService,
    WebinarFields
};

//forms
use app\models\{
    AddEventForm,
    EditFieldForm,
    ChangeDataForm,
    LogisticsForm,
    FinanceForm,
    AddSponsorForm,
    EventTicketForm,
    EventServiceForm
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
//        if($eventModel->find()->with('type')->where(compact($id))->one()->type->name === 'Вебинар' ) {
//             return $this->redirect('/webinar/' . $id);
//        }
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
        
        $tickets = EventTicket::find()->where($where)->asArray()->all();
        
        $services = EventService::find()
                ->where([
                    'is_deleted' => 0,
                    'event_id' => $id
                    ])
                ->with('city')
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
                'finance',
                'tickets',
                'services'
            )
        );
    }
    
    
    public function actionAdd() {
        $title = "Добавить событие";
        $model = new AddEventForm();
        
        if ($model->load(Yii::$app->request->post())) {
            $model->insertEvent();
            return $this->goBack();
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
                    //last update
                    Event::setLastUpdateTime($event_id);
                    return $this->redirect(['event', 'id' => $event_id]);
                }
                
                if(!$edit_form->uploadFile($file, $event_id, $field_id)){
                    throw new \yii\base\ErrorException("Невозможно загрузить файл!");
                }
                //last update
                Event::setLastUpdateTime($event_id);
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
            
            //last update
            Event::setLastUpdateTime($event_id);
            return $this->redirect(['event', 'id' => $event_id]);
        }
        
        /*обработчик формы: конец*/
        $form = new EditFieldForm();
        $model = $form->findOne($where);
        if(!$model) {
            $model = $form;
        }
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
            
            //last update
            Event::setLastUpdateTime($event_id);
            //ВЕБИНАР
            if(Event::find()->where(['id' => $event_id])->with('type')->one()->type->name === 'Вебинар') {
                return $this->redirect('/webinar/' . $event_id);
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
            //last update
            Event::setLastUpdateTime($event_id);
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
            //last update
            Event::setLastUpdateTime($event_id);
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
        
        $model = $form->findOne($where);
        
        /* delete */
        if( Yii::$app->request->post('remove') ) {
            $currentRow = LogisticInfo::findOne($item_id);
            $currentRow->is_deleted = 1;
            $currentRow->update();
            //last update
            Event::setLastUpdateTime($event_id);
            return $this->redirect(['event', 'id' => $event_id]);
        }
        /* end delete */
        
        $form = new LogisticsForm();
        /* begin handler form */
        if ($model->load(Yii::$app->request->post())) {
//            $postData = Yii::$app->request->post('LogisticsForm');
            if( !$model->updateData($item_id) ) {
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
            //last update
            Event::setLastUpdateTime($event_id);
            return $this->redirect(['event', 'id' => $event_id]);
        }
        
        $fieldsModel = new FinanceFields();
        $fields = $fieldsModel
                ->find()
                ->where(['is_deleted' => 0])
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
            //last update
            Event::setLastUpdateTime($event_id);
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
        //last update
        Event::setLastUpdateTime($event_id);
        return $this->redirect('/event/' . $event_id);
   
    }
    
    public function actionCancel($event_id) {
        
        $eventModel = new Event();
        $event = $eventModel->findOne($event_id);
        
        $event->is_cancel = 1;
        $event->update();
        //last update
        Event::setLastUpdateTime($event_id);
        return $this->redirect('/event/' . $event_id);
        
        
    }
    
    public function actionAbortCancel($event_id) {
        $eventModel = new Event();
        $event = $eventModel->findOne($event_id);
        
        $event->is_cancel = 0;
        $event->update();
        //last update
        Event::setLastUpdateTime($event_id);
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
        //last update
        Event::setLastUpdateTime($event_id);
        return $this->redirect([
            'event', 
            'id' => $event_id
                ]);
        
    }
    
    public function actionTruncateField($event_id, $field_id) {
        $EventInfo= new EventInfo();
        $field = $EventInfo->findOne(compact('event_id', 'field_id'));
        $field->value = null;
        $field->save();
        //last update
        Event::setLastUpdateTime($event_id);
        return $this->redirect([
            'event', 
            'id' => $event_id
                ]);
    }
    
    public function actionAddTicket($event_id) {
        $title = "Добавить билет";
        $model = new EventTicketForm();
        $event = Event::findOne(['id' => $event_id]);
        if($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'ticket_file');
            if(empty($file)) {
                return $this->redirect(['event', 'id' => $event_id]);
            }

            if(!$model->uploadFile($file, $event_id)){
                throw new \yii\base\ErrorException("Невозможно загрузить файл!");
            }
            //last update
            Event::setLastUpdateTime($event_id);
            return $this->redirect(['event', 'id' => $event_id]);
        }
        return $this->render('add_ticket', 
                compact(
                        'title',
                        'event',
                        'model'
                        )
                );
    }
    
    public function actionDeleteTicket($id, $event_id) {
        
        $ticket = EventTicket::findOne($id);
        @unlink(Yii::$app->params['pathUploads'] . 'event_files/' . $event_id . '/' . $ticket->filename);
        $ticket->is_deleted = 1;
        $ticket->update();
        //last update
        Event::setLastUpdateTime($event_id);
        return $this->redirect(['event', 'id' => $event_id]);
        
        
    }
    
    public function actionAddService($event_id) {
        $title = "Добавить доп. услугу";
        $model = new EventServiceForm();
        $event = Event::findOne($event_id);
        $cities= City::findAll(['is_deleted' => 0]);
        $city_items = ArrayHelper::map($cities, 'id', 'name');
        
        if(!$event) {
            throw new \yii\web\NotFoundHttpException("Нет события с таким ID");
        }
        
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            //last update
            Event::setLastUpdateTime($event_id);
            return $this->redirect(['event', 'id' => $event_id]);
        }
        return $this->render('add_service', 
                compact(
                        'title',
                        'event',
                        'model',
                        'city_items'
                        )
                );
        
    }
    
    public function actionEditService($event_id, $id) {
        $title = "Править доп. услугу";
        $model = EventServiceForm::findOne($id);
        if(!$model) {
            throw new \yii\web\NotFoundHttpException("Нет доп. услуги с таким ID");
        }
        $event = Event::findOne($event_id);
        if(!$event) {
            throw new \yii\web\NotFoundHttpException("Нет события с таким ID");
        }
        $cities= City::find()
                ->where(['is_deleted' => 0])
                ->orderBy(['name' => SORT_ASC])
                ->all();
        $city_items = ArrayHelper::map($cities, 'id', 'name');
        if($model->load(Yii::$app->request->post()) && $model->save()) {
            //last update
            Event::setLastUpdateTime($event_id);
            return $this->redirect(['event', 'id' => $event_id]);
        }
        return $this->render('edit_service', 
                compact(
                        'title',
                        'event',
                        'model',
                        'city_items'
                        )
                );
        
    }
    
    public function actionDeleteService($event_id, $id) {
        $service = EventService::findOne(compact('id', 'event_id'));
        $service->is_deleted = 1;
        $service->save();
        //last update
        Event::setLastUpdateTime($event_id);
        return $this->redirect(['event', 'id' => $event_id]);
    }
  
    
}