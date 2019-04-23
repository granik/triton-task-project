<?php

namespace app\modules\admin\models;
use app\models\InfoFields;
/**
 * Description of CityForm
 *
 * @author Granik
 */
class InfoFieldsForm extends InfoFields {
    
    public $option1;
    public $option2;
    public $option3;
    public $option4;
    public $option5;
    
    public function rules() {
        return [
                    ['name', 'string', 'min' => 2, 'max' => 30],
                    ['name', 'required'],
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
