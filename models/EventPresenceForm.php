<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;
use yii\base\Model;
use app\models\Event;


/**
 * Description of SetPresenceForm
 *
 * @author Granik
 */
class EventPresenceForm extends Model {

    
    public $presence;
    public $presence_comment;
    //put your code here
    public function attributeLabels() {
        return [
            'presence' => 'Итоговая явка',
            'presence_comment' =>  'Итоговый комментарий'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['presence', 'integer', 'min' => 0, 'max' => 10000000],
            ['presence_comment', 'string', 'min' => 2, 'max' => 150]
        ];
    }
    
    public function updateEventPresence($event_id) {
        if(!$this->validate()) {
            throw new \yii\base\ErrorException("Ошибка валидации данных формы!");
        }
        $event = new Event();
        $model = $event->findOne($event_id);
        $model->presence = $this->presence;
        $model->presence_comment = $this->presence_comment;
        return $model->save() ? true : false;
    }
}
