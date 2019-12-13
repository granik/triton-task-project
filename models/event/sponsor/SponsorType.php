<?php

namespace app\models\event\sponsor;

use yii\db\ActiveRecord;

/**
 * Модель типов спонсоров
 *
 * @author Granik
 */
class SponsorType extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'sponsor_type';
    }

    /**
     * Связь с моделью спонсоров
     * @return \yii\db\ActiveQuery
     */
    public function getSponsors()
    {
        return $this->hasMany(Sponsor::className(), ['type_id' => 'id']);
    }
}
