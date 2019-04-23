<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use yii\db\ActiveRecord;
use app\models\EventInfo;
use app\models\FieldType;
/**
 * Description of InfoFields
 *
 * @author Granik
 */
class InfoFields extends ActiveRecord {
    public static function tableName() {
        return 'info_fields';
    }
    
    public function getData($event_id, $field_id) {
        return $this
            ->find()
            ->with(['type', 'info' => function(\yii\db\ActiveQuery $query) use ($event_id) {
                $query->andWhere('event_id = ' . $event_id);
            }])
            ->where(['id' => $field_id])
            ->asArray()
            ->one();
    }
    
    public function getInfo() {
        return $this->hasOne(EventInfo::className(), ['field_id' => 'id']);
    }
    
    public function getType() {
        return $this->hasOne(FieldType::className(), ['id' => 'type_id']);
    }
    

   
}
