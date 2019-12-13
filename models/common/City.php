<?php

namespace app\models\common;

use yii\db\ActiveRecord;

/**
 * Модель городов
 *
 * @author Granik
 */
class City extends ActiveRecord
{

    public static function tableName()
    {
        return 'city';
    }

}
