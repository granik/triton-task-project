<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use yii\db\ActiveRecord;
use app\models\EventInfo;
/**
 * Description of AddEventForm
 *
 * @author Granik
 */
class EditFieldForm extends EventInfo {
//    public $title;
//    public $category_id;
//    public $type_id;
//    public $date;
//    public $city_id;
    public $file_single;
//    public $file_one;
//    public $file_two;
    
    public function rules() {
        return [
            ['value', 'trim'],
            ['comment', 'trim'],
            [['value'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 100],
            [['file_single'], 'file', 'extensions' => 'pdf, doc, docx, txt', 
                    'skipOnEmpty' => true],
//            [['file_one'], 'file', 'extensions' => 'pdf', 
//                    'skipOnEmpty' => true],
//            [['file_two'], 'file', 'extensions' => 'doc, docx', 
//                    'skipOnEmpty' => true]
            ];
    }
    
    public function attributeLabels() {
        return['value' => 'Значение',
               'comment' => 'Комментарий',
//               'file_one' => 'PDF: ',
//               'file_two' => 'Word: ',
               'file_single' => 'Файл: '];
    }
    
    public function updateData($event_id, $field_id) {
        if( !$this->validate() ) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        $field = $this->findOne( compact('event_id', 'field_id') );
        $field->value = $this->value;
        $field->comment = $this->comment;
        
        return $field->save() ? true : false;
    }
    
}
