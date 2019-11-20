<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\event\main;
use yii\db\ActiveRecord;
/**
 * Description of EventInfo
 *
 * @author Granik
 */
class EventInfo extends ActiveRecord {
    public static function tableName() {
        return 'event_info';
    }
    
    public function getFieldForEvent($event_id, $field_id) {
        return $this
                ->find()
                ->where(compact('event_id'))
                ->andWhere(compact('field_id'))
                ->asArray()
                ->one();
    
    
    }
//    
//    public function getFtype() {
//        return $this->hasOne(FieldType::className(), ['id' => 'field_id']);
//    }
    

}
