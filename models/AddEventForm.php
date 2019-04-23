<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use app\models\Event;
/**
 * Description of AddEventForm
 *
 * @author Granik
 */
class AddEventForm extends Event {
//    public $title;
//    public $category_id;
//    public $type_id;
//    public $date;
//    public $city_id;
    
    public function rules() {
        return [
            [['title', 'type_id', 'date', 'city_id', 'category_id'], 'required'],
            ['title', 'string', 'min' => 3, 'max' => 200],
            [['type_id', 'city_id', 'category_id'], 'integer'],
            ['date', 'date', 'format' => 'php:Y-m-d']
            ];
    }
    
    public function attributeLabels() {
        return['title' => 'Название',
            'type_id' => 'Тип события',
            'date' => 'День проведения',
            'city_id' => 'Город',
            'category_id' => 'Категория'];
    }
    
}
