<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use app\models\Event;

/**
 * Description of ChangeDataForm
 *
 * @author Granik
 */
class ChangeDataForm extends Event {
    
    public function rules() {
        return [
            ['id', 'integer'],
            [['title', 'type_id', 'date', 'city_id', 'category_id'], 'required'],
            [['title'], 'string', 'min' => 3, 'max' => 200],
            [['type_id', 'city_id', 'category_id'], 'integer'],
            ['date', 'date', 'format' => 'php:Y-m-d']
            ];
    }
    
    public function attributeLabels() {
        return[
            'id' => '',
            'title' => 'Название',
            'type_id' => 'Тип события',
            'date' => 'День проведения',
            'city_id' => 'Город',
            'category_id' => 'Категория'];
    }
    
    public function updateData() {
        
        if( !$this->validate() ) {
            throw new \yii\base\ErrorException("Ошибка валидации данных!");
        }
        
        $event = $this->findOne($this->id);
        $event->title = $this->title;
        $event->type_id = $this->type_id;
        $event->date = $this->date;
        $event->city_id = $this->city_id;
        $event->category_id = $this->category_id;
        //time of the last update
        Event::setLastUpdateTime($this->id);
        
        return $event->save() ? true : false;
    }
}
