<?php

namespace app\models\event\logistics;

use yii\db\ActiveRecord;

/**
 * Модель полей логистического инфо
 *
 * @author Granik
 */
class LogisticFields extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'logistic_fields';
    }
}
