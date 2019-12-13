<?php

namespace app\models\webinar;

use yii\db\ActiveRecord;

/**
 * Модель основного инфо для вебинара
 *
 * @author Granik
 */
class WebinarInfo extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return 'webinar_info';
    }
}
