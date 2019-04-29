<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use app\models\EventService;
/**
 * Description of EventServiceForm
 *
 * @author Granik
 */
class EventServiceForm extends EventService {
    //put your code here
    public function rules() {
        return [
            ['title', 'required'],
            ['title', 'string', 'min' => 2, 'max' => 30],
            ['customer', 'required'],
            ['customer', 'string', 'min' => 2, 'max' => 30],
            ['producer', 'required'],
            ['producer', 'string', 'min' => 2, 'max' => 30],
            ['city_id', 'integer'],
            ['date', 'required'],
            ['date', 'date', 'format' => 'php:Y-m-d'],
            ['people_amount', 'integer'],
            ['event_id', 'integer'],
            ['status', 'integer']
        ];
    }
    
    public function attributeLabels() {
        return [
            'event_id' => '',
            'title' => 'Название',
            'customer' => 'Заказчик',
            'producer' => 'Исполнитель',
            'city_id' => 'Город отпр.',
            'date' => 'Дата',
            'people_amount' => 'Кол-во чел.',
            'status' => 'Оплачен'
        ];
    }
}
