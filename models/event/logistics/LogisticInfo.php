<?php

namespace app\models\event\logistics;

use yii\db\ActiveRecord;

/**
 * Модель логистического инфо
 *
 * @author Granik
 */
class LogisticInfo extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'logistic_info';
    }

    /**
     * Связь с типом поля
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(LogisticFields::className(), ['id' => 'type_id']);
    }

    /**
     * Связь со средствами передвижения
     * (поездка туда)
     * @return \yii\db\ActiveQuery
     */
    public function getTo()
    {
        return $this->hasOne(LogisticMeans::className(), ['id' => 'to_means']);
    }

    /**
     * Связь со средствами передвижения
     * (поездка между городами)
     * @return \yii\db\ActiveQuery
     */
    public function getBetween()
    {
        return $this->hasOne(LogisticMeans::className(), ['id' => 'between_means']);
    }

    /**
     * Связь со средствами передвижения
     * (поездка домой)
     * @return \yii\db\ActiveQuery
     */
    public function getHome()
    {
        return $this->hasOne(LogisticMeans::className(), ['id' => 'home_means']);
    }
}
