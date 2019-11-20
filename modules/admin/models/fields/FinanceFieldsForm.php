<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\admin\models\fields;

use app\models\event\finance\FinanceFields;
/**
 * Description of FinanceFieldsForm
 *
 * @author Granik
 */
class FinanceFieldsForm extends FinanceFields {
    
    public function rules() {
        return [
                    ['name', 'string', 'min' => 2, 'max' => 20],
                    ['name', 'required'],
                    ['name', 'unique', 'targetClass' => FinanceFields::className(), 
                        'message' => 'Такой тип спонсора уже существует!', 
                        'filter' => ['<>', 'id', $this->id ?? 0]
                        ]
               ];
    }
    
    public function attributeLabels() {
        return [
            'name' => 'Название поля'
        ];
    }
    
    public function createNew() {
        if( !$this->validate() ) {
            throw new ErrorException("Ошибка валидации данных!");
        }
        $deleted = $this->find()->where(['name' => 'removed-'. trim($this->name)])->one();
        if(!empty($deleted)) {
            //если уже есть такая, но удалена
            $deleted->name = str_replace('removed-', '', $deleted->name);
            $deleted->is_deleted = 0;
            return $deleted->save() ? true : false;
        } 
        
        return $this->save() ? true : false;
    }
}
