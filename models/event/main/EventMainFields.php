<?php

namespace app\models\event\main;

use yii\db\ActiveRecord;
use app\models\event\main\EventInfo;

/**
 * Модель полей основного инфо о событии
 *
 * @author Granik
 */
class EventMainFields extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'info_fields';
    }

    /**
     * Связь со значением поля
     *
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(EventInfo::className(), ['field_id' => 'id']);
    }

    /**
     * Связь с типом поля
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(FieldType::className(), ['id' => 'type_id']);
    }

}
