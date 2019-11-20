<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\event\main;
use yii\db\ActiveRecord;

/**
 * Description of FieldTypes
 *
 * @author Granik
 */
class FieldType extends ActiveRecord {
    public static function tableName() {
        return 'field_type';
    }
    
//    public function getFields() {
//        return $this->hasOne(InfoFields::className(), ['type_id' => 'id']);
//    }
    
    
}
