<?php

namespace app\models\event\tickets;

use yii\db\ActiveRecord;

/**
 * Модель электронных билетов
 *
 * @author Granik
 */
class EventTicket extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'event_ticket';
    }
}
