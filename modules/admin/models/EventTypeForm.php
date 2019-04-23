<?php


namespace app\modules\admin\models;
use app\models\EventType;
/**
 * Description of CityForm
 *
 * @author Granik
 */
class EventTypeForm extends EventType {
    
    public function rules() {
        return [
                    ['name', 'string', 'min' => 2, 'max' => 30],
                    ['name', 'required']
               ];
    }
    
    public function attributeLabels() {
        return [
            'name' => 'Тип события'
        ];
    }
}