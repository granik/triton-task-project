<?php
namespace app\models;
use yii\db\ActiveRecord;
use app\models\WebinarFields;
/**
 * Description of EventInfo
 *
 * @author Granik
 */
class WebinarInfo extends ActiveRecord {
    
    public static function tableName() {
        return 'webinar_info';
    }
    
    public function getFieldForWebinar($webinar_id, $field_id) {
        return $this
                ->find()
                ->where(compact('webinar_id'))
                ->andWhere(compact('field_id'))
                ->asArray()
                ->one();
    }
}
