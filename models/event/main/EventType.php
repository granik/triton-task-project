<?php

namespace app\models\event\main;

use yii\db\ActiveRecord;

/**
 * Модель типов событий
 *
 * @author Granik
 */
class EventType extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'event_type';
    }

}
