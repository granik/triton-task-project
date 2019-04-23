<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use yii\db\ActiveRecord;
use app\models\Sponsor;

/**
 * Description of SponsorType
 *
 * @author Granik
 */
class SponsorType extends ActiveRecord {
    //put your code here
    public static function tableName() {
        return 'sponsor_type';
    }
    
    public function getSponsors() {
        return $this->hasMany(Sponsor::className(), ['type_id' => 'id']);
    }
}
