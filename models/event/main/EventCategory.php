<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\event\main;
use yii\db\ActiveRecord;
/**
 * Description of SectionModel
 *
 * @author Granik
 */
class EventCategory extends ActiveRecord {
    //put your code here
    public static function tableName() {
        return 'event_category';
    }
    
    public function getCategories() {
        return $this->find()
                ->where(['is_deleted' => 0])
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->all();
    }
}
