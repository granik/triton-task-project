<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\event\services;
use yii\db\ActiveRecord;
use app\models\common\City;
/**
 * Description of EventService
 *
 * @author Granik
 */
class EventService extends ActiveRecord {
    
    public static function tableName() {
        return 'event_service';
    }
    
    public function getCity() {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
}
