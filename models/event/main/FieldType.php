<?php

namespace app\models\event\main;

use yii\db\ActiveRecord;

/**
 * Модель типов полей события
 *
 * @author Granik
 */
class FieldType extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'field_type';
    }
}
