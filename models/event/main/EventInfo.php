<?php

namespace app\models\event\main;

use yii\db\ActiveRecord;

/**
 * Модель основной информации о событии
 *
 * @author Granik
 */
class EventInfo extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'event_info';
    }


}
