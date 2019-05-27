<?php

namespace app\modules\admin\models;
use app\models\MossemFields;
/**
 * Description of CityForm
 *
 * @author Granik
 */
class MossemFieldsForm extends MossemFields {
    
    public $option1;
    public $option2;
    public $option3;
    public $option4;
    public $option5;
    
    public function rules() {
        return [
                    ['name', 'string', 'min' => 2, 'max' => 30],
                    ['name', 'required'],
                    ['name', 'unique', 'targetClass' => '\app\models\WebinarFields', 
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
    
//    public function createNew() {
//        if( !$this->validate() ) {
//            throw new ErrorException("Ошибка валидации данных!");
//        }
//        $deleted = $this->find()->where(['name' => 'removed-'. trim($this->name)])->one();
//        if(!empty($deleted)) {
//            //если уже есть такая, но удалена
//            $deleted->name = str_replace('removed-', '', $deleted->name);
//            $deleted->is_deleted = 0;
//            return $deleted->save() ? true : false;
//        } 
//        
//        return $this->save() ? true : false;
//    }
}
