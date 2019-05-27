<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use yii\db\ActiveRecord;
use app\models\MossemInfo;
use app\models\FieldType;
/**
 * Description of InfoFields
 *
 * @author Granik
 */
class MossemFields extends ActiveRecord {
    public static function tableName() {
        return 'mossem_fields';
    }
    
    public function getInfo() {
        return $this->hasOne(MossemInfo::className(), ['field_id' => 'id']);
    }
    
    public function getType() {
        return $this->hasOne(FieldType::className(), ['id' => 'type_id']);
    }

}
