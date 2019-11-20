<?php

namespace app\models\webinar;

use yii\db\ActiveRecord;
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
