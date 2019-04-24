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
            ['name', 'trim'],
            ['name', 'unique', 'targetClass' => '\app\models\LogisticFields', 
                        'message' => 'Такое поле уже существует!', 
                'filter' => ['=', 'is_deleted', 0]
                ]
        ];
    }
    
    public function attributeLabels() {
        return [
            'name' => 'Имя поля'
        ];
    }
}
