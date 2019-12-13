<?php

namespace app\models\mossem;

use yii\db\ActiveRecord;
use app\models\event\main\FieldType;

/**
 * Модель полей главного инфо мос. семинара
 *
 * @author Granik
 */
class MossemFields extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'mossem_fields';
    }

    /**
     * СВязь со значением поля
     *
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(MossemInfo::className(), ['field_id' => 'id']);
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
