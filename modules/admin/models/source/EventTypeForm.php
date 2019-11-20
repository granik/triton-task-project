<?php


namespace app\modules\admin\models\source;

use app\models\event\main\EventType;
/**
 * Description of CityForm
 *
 * @author Granik
 */
class EventTypeForm extends EventType {
    
    public function rules() {
        return [
                    ['name', 'string', 'min' => 2, 'max' => 30],
                    ['name', 'unique', 'targetClass' => '\app\models\EventType', 
                        'message' => 'Такой тип события уже существует!',
                        'filter' => ['<>', 'id', $this->id ?? 0]
                        ],
                    ['name', 'required'],
                    ['color', 'string', 'length' => 7]
               ];
    }
    
    public function attributeLabels() {
        return [
            'name' => 'Тип события',
            'color' => 'Цвет в таблице'
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