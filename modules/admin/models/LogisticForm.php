<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\admin\models;

use app\models\LogisticFields;
/**
 * Description of LogisticForm
 *
 * @author Granik
 */
class LogisticForm extends LogisticFields {
    
    public function rules() {
        return [
            ['name', 'string', 'min' => 3, 'max' => 20],
            ['name', 'trim']
        ];
    }
    
    public function attributeLabels() {
        return [
            'name' => 'Имя поля'
        ];
    }
}
