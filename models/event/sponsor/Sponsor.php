<?php

namespace app\models\event\sponsor;

use yii\db\ActiveRecord;

/**
 * Description of Sponsor
 *
 * @author Granik
 */
class Sponsor extends ActiveRecord
{
    //put your code here
    public static function tableName()
    {
        return 'sponsor_info';
    }

    public function getType()
    {
        return $this->hasOne(SponsorType::className(), ['id' => 'type_id']);
    }

//    public function addSponsor($event_id, $name, $type) {
//        $this->insert
//    }
}
