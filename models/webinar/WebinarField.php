<?php

namespace app\models\webinar;

use yii\db\ActiveRecord;
use app\models\event\main\FieldType;

/**
 * Модель полей основного инфо вебинара
 *
 * @author Granik
 */
class WebinarField extends ActiveRecord
{

    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'webinar_fields';
    }

    /**
     * Связь со значением поля
     *
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(WebinarInfo::className(), ['field_id' => 'id']);
    }

    /**
     * Связь с типом поля
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(FieldType::className(), ['id' => 'type_id']);
    }
}
