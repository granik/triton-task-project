<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\event\finance;
use yii\db\ActiveRecord;

/**
 * Description of financeInfo
 *
 * @author Granik
 */
class FinanceInfo extends ActiveRecord {
    public static function tableName() {
        return 'finance_info';
    }
    
    public function getFields() {
        return $this->hasOne(FinanceFields::className(), ['id' => 'type_id']);
    }
}
