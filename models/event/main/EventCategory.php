<?php

namespace app\models\event\main;

use yii\db\ActiveRecord;

/**
 * Модель категорий событий
 *
 * @author Granik
 */
class EventCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'event_category';
    }
}
