<?php
namespace app\models;
use yii\db\ActiveRecord;
use app\models\MossemFields;
/**
 * Description of EventInfo
 *
 * @author Granik
 */
class MossemInfo extends ActiveRecord {
    
    public static function tableName() {
        return 'mossem_info';
    }
    
    public function getFieldForWebinar($mossem_id, $field_id) {
        return $this
                ->find()
                ->where(compact('mossem_id'))
                ->andWhere(compact('field_id'))
                ->asArray()
                ->one();
    }
}
