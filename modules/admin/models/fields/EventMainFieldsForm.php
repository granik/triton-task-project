<?php

namespace app\modules\admin\models\fields;

use app\models\event\main\EventMainFields;
/**
 * Description of CityForm
 *
 * @author Granik
 */
class EventMainFieldsForm extends EventMainFields {
    
    public $option1;
    public $option2;
    public $option3;
    public $option4;
    public $option5;
    
    public function rules() {
        return [
                    ['name', 'string', 'min' => 2, 'max' => 30],
                    ['name', 'required'],
                    ['name', 'unique', 'targetClass' => EventMainFields::classname(),
                        'message' => 'Такое поле уже существует!', 
                        'filter' => ['<>', 'id', $this->id ?? 0]
                        ],
                    ['type_id', 'integer'],
                    [['position', 'has_comment', 'options', ], 'safe'],
                    [
                        [
                            'option1',
                            'option2',
                            'option3',
                            'option4',
                            'option5',
                        ],
                        'string', 'min' => 2, 'max' => 30]
               ];
    }
    
    public function attributeLabels() {
        return [
            'name' => 'Имя поля',
            'type_id'  => 'Тип поля',
            'has_comment' => 'С комментарием',
            'option1' => '',
            'option2' => '',
            'option3' => '',
            'option4' => '',
            'option5' => '',
        ];
    }

}
