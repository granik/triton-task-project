<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use yii\db\ActiveRecord;
use app\models\Event;
use app\models\LogisticMeans;
use app\models\LogisticFields;

/**
 * Description of LogisticInfo
 *
 * @author Granik
 */
class LogisticInfo extends ActiveRecord {
    public static function tableName() {
        return 'logistic_info';
    }
    
//    public function getEvent() {
//        return $this->hasOne(Event::className(), ['event_id' => 'id']);
//    }
    
    public function getType() {
        return $this->hasOne(LogisticFields::className(), ['id' => 'type_id']);
    }
    
    public function getTo() {
        return $this->hasOne(LogisticMeans::className(), ['id' => 'to_means']);
    }
    
    public function getBetween() {
        return $this->hasOne(LogisticMeans::className(), ['id' => 'between_means']);
    }
    
    public function getHome() {
        return $this->hasOne(LogisticMeans::className(), ['id' => 'home_means']);
    }
}
