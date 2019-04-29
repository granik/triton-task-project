<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use Yii;
use app\models\EventInfo;
/**
 * Description of AddEventForm
 *
 * @author Granik
 */
class EditFieldForm extends EventInfo {

    
    public $file_single;

    
    public function rules() {
        return [
            ['value', 'trim'],
            ['value', 'string', 'max' => 100],
            ['comment', 'string', 'max' => 100],
            ['comment', 'trim'],
            ['file_single', 'file', 'extensions' => 'pdf, doc, docx, txt', 
                    'skipOnEmpty' => true],
            ];
    }
    
    public function attributeLabels() {
        return['value' => 'Значение',
               'comment' => 'Комментарий',
               'file_single' => 'Загрузить файл: '];
    }
    
    public function createNew($event_id, $field_id) {
        if( !$this->validate() ) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        
        $model = new EventInfo();
        $model->field_id = $field_id;
        $model->event_id = $event_id;
        $model->value = $this->value;
        $model->comment = $this->comment;
        
        return $model->save() ? true : false;
    }
    
    public function updateData($event_id, $field_id) {
        if( !$this->validate() ) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        $field = $this->findOne( compact('event_id', 'field_id') );
        if(empty($field)) {
            $field = $this;
            $field->field_id = $field_id;
            $field->event_id = $event_id;
        } 
        $field->value = $this->value;
        $field->comment = $this->comment;
        //last update
        Event::setLastUpdateTime($event_id);
        
        return $field->save() ? true : false;
    }
    
    public function updateOnlyComment($event_id, $field_id) {
        //для файловых полей
        if( !$this->validate() ) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        $field = $this->findOne( compact('event_id', 'field_id') );
        if(empty($field)) {
            $field = $this;
            $field->field_id = $field_id;
            $field->event_id = $event_id;
            
        } 
        $field->comment = $this->comment;
        
        return $field->save() ? true : false;
    }
    
    public function uploadFile($file, $event_id, $field_id) {
        
        if(!$this->validate() ) {
            throw new \yii\base\ErrorException("Ошибка валидации данных");
        }
        //пришел файл
        $path = Yii::$app->params['pathUploads'] . 'event_files/' . $event_id . '/';
        if(!is_dir($path)) {
            mkdir($path, 0755);
        }
        $model = new EventInfo();
        $field = $model->findOne(compact('event_id', 'field_id'));
        
        
        
        if( empty($field) ) {
           $field = $model;
        } else if(null !== $field->value) {
            @unlink($path . $field->value);
        }
        
        $baseName = $file->getBaseName();
        $ext = $file->getExtension();
        $field->event_id = $event_id;
        $field->field_id = $field_id;
        $field->value = $baseName . '.' . $ext;
        $field->comment = $this->comment;
        $i = 1;
        while(file_exists($path . $field->value)) {
            $field->value = $baseName . "($i)." . $ext;
            $i++;
        }
        
        return $field->save() && $file->saveAs( $path . $field->value ) ? true : false;
    }
    
}
