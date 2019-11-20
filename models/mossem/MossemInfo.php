<?php
namespace app\models\mossem;
use yii\db\ActiveRecord;
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
