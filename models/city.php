<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use yii\db\ActiveRecord;

/**
 * Description of City
 *
 * @author Granik
 */
class City extends ActiveRecord {
    
    public static function tableName() {
        return 'city';
    }
    
    public function getCitiesAsArray() {
        return $this->find()
                ->where(['is_deleted' => 0])
                ->asArray()
                ->all();
    }
}
