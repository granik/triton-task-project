<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\webinar;
use yii\db\ActiveRecord;
use app\models\event\main\FieldType;
/**
 * Description of InfoFields
 *
 * @author Granik
 */
class WebinarFields extends ActiveRecord {
    public static function tableName() {
        return 'webinar_fields';
    }
    
    public function getInfo() {
        return $this->hasOne(WebinarInfo::className(), ['field_id' => 'id']);
    }
    
    public function getType() {
        return $this->hasOne(FieldType::className(), ['id' => 'type_id']);
    }

}
