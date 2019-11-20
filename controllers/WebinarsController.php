<?php

namespace app\controllers;

use app\models\webinar\WebinarFields;
use app\models\webinar\WebinarInfo;
use app\models\webinar\EditFieldWebinarForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use app\components\Functions;
use app\models\event\main\Event;
use app\models\event\main\EventCategory;
use app\models\common\City;
use app\models\event\main\EventType;
use app\models\event\sponsor\Sponsor;
use app\models\event\sponsor\SponsorType;
use app\models\event\main\ChangeDataForm;
use app\models\event\sponsor\AddSponsorForm;

/**
 * Description of WebinarsController
 *
 * @author Granik
 */
class WebinarsController extends AppController{
    
    //week days
    private $days = ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'];
    //put your code here
    public function actionWebinar($id) {
       $title = "Страница вебинара";
       $webinar = Event::find()
           ->select(['event.*', 'event_type.name as type', 'event_category.name as category'])
           ->innerJoin('event_type', 'event.type_id = event_type.id')
           ->innerJoin('event_category', 'event.category_id = event_category.id')
           ->where(['event.id' => $id, 'event.is_deleted' => 0])
           ->asArray()
           ->one();
       if(empty($webinar)) {
           throw new \yii\web\NotFoundHttpException("Страница не найдена");
       }
       $days = $this->days;
       $timestamp = strtotime($webinar['date']);
       $webinar['date'] = Functions::toSovietDate($webinar['date']);
       $webinar['date_weekday'] = $webinar['date'] . ' (' . $days[date("w", $timestamp)] . ')';

       $model = new WebinarFields();
       $data = $model::find()->with([ 
       'info' => function (\yii\db\ActiveQuery $query) use ($id) {

       $query->andWhere('webinar_id = '. $id);
       },])
           ->leftJoin('webinar_info', 'field_id')
           ->where(['webinar_fields.is_deleted' => 0])
               //чтобы если поле было удалено из админки, 
               //в событиях заполненная инфа осталась
           ->orWhere("webinar_info.value <> '' AND webinar_info.field_id = webinar_fields.id")
           ->orderBy(['position' => SORT_ASC])
           ->asArray()
           ->all();

       $sponsor = new Sponsor();
       $sponsors = $sponsor
               ->find()
               ->with('type')
               ->where(['event_id' => $id, 'is_deleted' => 0])
               ->asArray()
               ->all();

       return $this->render('webinar', 
           compact(
               'title',
               'id',
               'data',
               'webinar',
               'sponsors'
           )
       );
   }
    
    public function actionEditField($webinar_id, $field_id) {
        $title = "Редактирование";
        $edit_form = new EditFieldWebinarForm();
        $where = compact('webinar_id', 'field_id');
        
        
        /*обработчик для поля с файлом: начало*/
        
        /*  обработчик для поля с файлом: конец */
        $webinarFields = new WebinarFields();
        //получаем данные указанного поля
        $field = $webinarFields
                    ->find()
                    ->with(['info' => function(\yii\db\ActiveQuery $query) use ($webinar_id) {
                        $query->andWhere('webinar_id = ' . $webinar_id);
                    }])
                    ->where(['id' => $field_id])
                    ->one();
                    
        if ($edit_form->load( Yii::$app->request->post() )) {
            
            if($field->type->name == 'file') {
                //пришел файл
                $file = UploadedFile::getInstance($edit_form, 'file_single');
                if(empty($file)) {
                    //пришло пустое файловое поле
                    $edit_form->updateOnlyComment($webinar_id, $field_id);
                    //last update
                    Event::setLastUpdateTime($webinar_id);
                    return $this->redirect(['webinar', 'id' => $webinar_id]);
                }
                
                if(!$edit_form->uploadFile($file, $webinar_id, $field_id)){
                    throw new \yii\base\ErrorException("Невозможно загрузить файл!");
                }
                //last update
                Event::setLastUpdateTime($webinar_id);
                return $this->redirect(['webinar', 'id' => $webinar_id]);
                
            }
            
            if(  null == WebinarInfo::find()->where($where)->one() ) {
                //если поле ранее не было заполнено
                if(!$edit_form->createNew($webinar_id, $field_id)) {
                    throw new \yii\base\ErrorException("Невозможно вставить данные!");
                }
             
            } else if( !$edit_form->updateData($webinar_id, $field_id) ) {
                //обновляем существующее
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }
            
            //last update
            Event::setLastUpdateTime($webinar_id);
            return $this->redirect(['webinar', 'id' => $webinar_id]);
        }
        
        /*обработчик формы: конец*/
        $form = new EditFieldWebinarForm();
        $model = $form->findOne($where);
        if(!$model) {
            $model = $form;
        }
        $eventModel = new Event();
        //получаем данные о событии (для breadcrumbs и title) 
        $event = $eventModel->getEventAsArray($webinar_id);
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
    
    public function actionChangeData($webinar_id) {
        $title = "Изменить вебинар";
        $change_form = new ChangeDataForm();
        /* begin form handler */
        if ($change_form->load(Yii::$app->request->post())) {
            if(!$change_form->updateData()) {
                throw new \yii\base\ErrorException("Невозможно обновить данные!");
            }
            //last update
            Event::setLastUpdateTime($webinar_id);
            return $this->redirect(['event', 'id' => $webinar_id]);
        }
        /* end form handler */
        $model = new ChangeDataForm();
        $event = $model->findOne($webinar_id);
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
    
    public function actionAddSponsor($webinar_id) {
        $title = "Добавить спонсора";
        $eventModel = new Event();
        $event = $eventModel->find()
                ->where(['id' => $webinar_id])
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
            Event::setLastUpdateTime($webinar_id);
            return $this->redirect(['webinar', 'id' => $webinar_id] );
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
    
        public function actionRemoveSponsor($id, $webinar_id) {
        $sponsor = Sponsor::find()->where(['id' => $id, 'event_id' => $webinar_id])->one();
        $sponsor->is_deleted = 1;
        $sponsor->update();
        //last update
        Event::setLastUpdateTime($webinar_id);
        return $this->redirect('/webinar/' . $webinar_id);
   
    }
    
    public function actionCancel($webinar_id) {
        
        $eventModel = new Event();
        $event = $eventModel->findOne($webinar_id);
        
        $event->is_cancel = 1;
        $event->update();
        //last update
        Event::setLastUpdateTime($webinar_id);
        return $this->redirect('/webinar/' . $webinar_id);
        
        
    }
    
    public function actionAbortCancel($webinar_id) {
        $eventModel = new Event();
        $event = $eventModel->findOne($webinar_id);
        
        $event->is_cancel = 0;
        $event->update();
        //last update
        Event::setLastUpdateTime($webinar_id);
        return $this->redirect('/webinar/' . $webinar_id);
        
        
    }
    
    public function actionUnlinkFile($webinar_id, $field_id) {
        $InfoFields = new WebinarFields();
        $field = $InfoFields
                    ->find()
                    ->with(['type', 'info' => function(\yii\db\ActiveQuery $query) use ($webinar_id) {
                        $query->andWhere('webinar_id = ' . $webinar_id);
                    }])
                    ->where(['id' => $field_id])
                    ->one();
        $fileField = WebinarInfo::findOne(compact('webinar_id', 'field_id'));
        
        if($field->type->name != 'file') {
            throw new \yii\base\ErrorException("Данное поле не имеет тип 'file'!");
        }
        
        
        @unlink(Yii::$app->params['pathUploads'] . 'event_files/' . $webinar_id . '/' . $fileField->value);
        $fileField->value = null;
        $fileField->save();
        //last update
        Event::setLastUpdateTime($webinar_id);
        return $this->redirect([
            'webinar', 
            'id' => $webinar_id
                ]);
        
    }
    
    public function actionTruncateField($webinar_id, $field_id) {
        $webinarInfo= new WebinarInfo();
        $field = $webinarInfo->findOne(compact('webinar_id', 'field_id'));
        $field->value = null;
        $field->save();
        //last update
        Event::setLastUpdateTime($webinar_id);
        return $this->redirect([
            'webinar', 
            'id' => $webinar_id
                ]);
    }
}
