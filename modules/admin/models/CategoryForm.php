<?php


namespace app\modules\admin\models;
use app\models\EventCategory;

/**
 * Description of CityForm
 *
 * @author Granik
 */
class CategoryForm extends EventCategory {
    
    
    public function rules() {
        return [
                    ['name', 'string', 'min' => 2, 'max' => 30],
                    ['name', 'required'],
                    ['name', 'unique', 'targetClass' => '\app\models\EventCategory', 
                        'message' => 'Такая категория уже существует!']
               ];
    }
    
    public function attributeLabels() {
        return [
            'name' => 'Название категории'
        ];
    }
}