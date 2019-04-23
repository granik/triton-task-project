<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use yii\db\ActiveRecord;
/**
 * Description of EventType
 *
 * @author Granik
 */
class EventType extends ActiveRecord {
    public static function tableName() {
        return 'event_type';
    }
    
    public function getEventTypes() {
        return self::find()
                ->where(['is_deleted' => 0])
                ->asArray()
                ->all();
    }
}
