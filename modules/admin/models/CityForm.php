<?php


namespace app\modules\admin\models;
use app\models\City;

/**
 * Description of CityForm
 *
 * @author Granik
 */
class CityForm extends City {
    
    public function rules() {
        return [
                    ['name', 'string', 'min' => 2, 'max' => 30],
                    ['name', 'required'],
                    ['name', 'unique', 'targetClass' => '\app\models\City',
                        'message' => 'Такой город уже существует!',
                        'filter' => ['=', 'is_deleted', 0]
                        ]
               ];
    }
    
    public function attributeLabels() {
        return [
            'name' => 'Название'
        ];
    }
}
