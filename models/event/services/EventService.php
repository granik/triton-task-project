<?php

namespace app\models\event\services;

use yii\db\ActiveRecord;
use app\models\common\City;

/**
 * Модель дополнительных услуг
 *
 * @author Granik
 */
class EventService extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'event_service';
    }

    /**
     * Связь с моделью городов
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
}
