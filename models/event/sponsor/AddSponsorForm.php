<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\event\sponsor;

/**
 * Description of AddSponsorForm
 *
 * @author Granik
 */
class AddSponsorForm extends Sponsor{
   
    
    public function rules() {
        return [
                [['name', 'type_id'], 'required'],
                ['name', 'string', 'min' => 3, 'max' => 50],
                [['event_id', 'type_id'], 'integer']
            ];
    }
    
    public function attributeLabels() {
        return [
            'name' => 'Имя спонсора',
            'type_id' => 'Тип спонсора',
            'event_id' => ''
        ];
    }
}
