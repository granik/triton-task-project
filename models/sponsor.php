<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use yii\db\ActiveRecord;
use app\models\SponsorType;

/**
 * Description of Sponsor
 *
 * @author Granik
 */
class Sponsor extends ActiveRecord {
    //put your code here
    public static function tableName() {
        return 'sponsor_info';
    }
    
    public function getType() {
        return $this->hasOne(SponsorType::className(), ['id' => 'type_id']);
    }
    
//    public function addSponsor($event_id, $name, $type) {
//        $this->insert
//    }
}
