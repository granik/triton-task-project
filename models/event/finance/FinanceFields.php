<?php

namespace app\models\event\finance;

use yii\db\ActiveRecord;

/**
 * Модель полей табоицы финансового инфо
 *
 * @author Granik
 */
class FinanceFields extends ActiveRecord
{
    public static function tableName()
    {
        return 'finance_fields';
    }
}
