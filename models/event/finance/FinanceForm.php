<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\event\finance;
/**
 * Description of addFinanceForm
 *
 * @author Granik
 */
class FinanceForm extends FinanceInfo {
    
    public function rules() {
        return [
                [['exist', 'status'], 'safe'],
                ['comment', 'string', 'min' => 1, 'max' => 50],
                [['event_id', 'type_id'], 'integer'],
                [['event_id', 'type_id'], 'required']
            ];
    }
    
    public function attributeLabels() {
        return [
            'comment' => 'Примечание',
            'status' => 'Статус',
            'type_id' => 'Тип',
            'exist' => 'Наличие',
            'event_id' => ''
        ];
    }
    
    public function updateData($id) {
        if( !$this->validate() ) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        
        $row = $this->findOne($id);
        $row->type_id = $this->type_id;
        $row->exist = $this->exist;
        $row->status = $this->status;
        $row->comment = $this->comment;
        
        return $row->save() ? true : false;
    }
}
