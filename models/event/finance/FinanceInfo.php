<?php

namespace app\models\event\finance;

use yii\db\ActiveRecord;

/**
 * Модель финансового инфо
 *
 * @author Granik
 */
class FinanceInfo extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'finance_info';
    }

    /**
     * Связь c типом поля
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(FinanceFields::className(), ['id' => 'type_id']);
    }
}
